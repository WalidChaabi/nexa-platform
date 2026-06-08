<?php

namespace App\Services;

use App\Events\ActivateAccount;
use App\Models\Campaign;
use App\Models\CampaignHistorique;
use App\Models\EmailCampaign;
use App\Models\SmsCrossSelling;
use App\User;
use CMI\CmiClient;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;

class CheckoutService
{
    const PENDING_PAYMENT_TTL_MINUTES = 1440;

    protected string  $base_url;
    protected $smsService;
    protected $historiquePaiementService;
    public function __construct()
    {
        $this->base_url =  config('app.sub_url');
        $this->smsService = new SmsService;
        $this->historiquePaiementService = new HistoriquePaiementService();
    }

    public function pendingPaymentCacheKey($cmd)
    {
        return 'cmi_pending_payment:' . $cmd;
    }

    public function cachePendingPayment($cmd, $ownerId, array $storeIds, array $invoiceDetail)
    {
        Cache::put($this->pendingPaymentCacheKey($cmd), [
            'owner_id' => (int) $ownerId,
            'store_ids' => $storeIds,
            'invoice_detail' => $invoiceDetail,
        ], now()->addMinutes(self::PENDING_PAYMENT_TTL_MINUTES));
    }

    public function clearPendingPayment($cmd)
    {
        Cache::forget($this->pendingPaymentCacheKey($cmd));
    }

    /**
     * Resolve owner, stores, and invoice detail when CMI redirects back (session may be gone).
     *
     * @return array{owner: User, store_ids: array, invoice_detail: array}
     */
    public function resolvePaymentContext($oid)
    {
        if (auth()->check()) {
            $user = auth()->user();
            $invoiceDetail = session()->get('detail_invoice' . $user->id);
            if ($invoiceDetail) {
                return [
                    'owner' => $user,
                    'store_ids' => $user->stores->pluck('id')->toArray(),
                    'invoice_detail' => $invoiceDetail,
                ];
            }
        }

        if (!$oid) {
            abort(403, 'Payment reference missing.');
        }

        $cached = Cache::get($this->pendingPaymentCacheKey($oid));
        if ($cached) {
            $owner = User::findOrFail($cached['owner_id']);

            return [
                'owner' => $owner,
                'store_ids' => $cached['store_ids'],
                'invoice_detail' => $cached['invoice_detail'],
            ];
        }

        $historique = $this->historiquePaiementService->getByInvoiceId($oid);
        if ($historique && $historique->owner) {
            return [
                'owner' => $historique->owner,
                'store_ids' => $historique->owner->stores->pluck('id')->toArray(),
                'invoice_detail' => [
                    'services' => $historique->services,
                    'cmd' => $historique->cmd,
                    'tva' => config('constant.sherin_tva'),
                    'owner_id' => $historique->owner_id,
                    'total_ht' => $historique->total_ht,
                    'total_ttc' => $historique->total_ttc,
                ],
            ];
        }

        abort(403, 'Payment session expired and could not be recovered.');
    }

    public function process(array $data = [])
    {
        $client = new CmiClient([
            'storekey' => config('app.CMI_STORE_KEY'), // STOREKEY
            'clientid' => config('app.CMI_CLIENT_ID'), // CLIENTID
            'CallbackResponse' => 'true',// RETRIEVE AMOUNT WITH METHOD POST
            'CallbackURL' => route('owner.callback-checkout'), // CALLBACK
            'oid' => $data['cmd'], // COMMAND ID IT MUST BE UNIQUE
            'shopurl' => route('owner.dashboard'), // SHOP URL FOR REDIRECTION
            'okUrl' => route('owner.success-checkout'), // REDIRECTION AFTER SUCCEFFUL PAYMENT
            'failUrl' => route('owner.fail-checkout'), // REDIRECTION AFTER FAILED PAYMENT
            'email' => $data['email'], // YOUR EMAIL APPEAR IN CMI PLATEFORM
            'BillToName' => clean($data['firstname'] . ' ' . $data['lastname']) , // YOUR NAME APPEAR IN CMI PLATEFORM
            'BillToCompany' => clean(config('app.name')), // YOUR COMPANY NAME APPEAR IN CMI PLATEFORM
            'BillToStreet1' => clean($data['address']), // YOUR ADDRESS APPEAR IN CMI PLATEFORM NOT REQUIRED
            'tel' => clean($data['phone']), // YOUR PHONE APPEAR IN CMI PLATEFORM NOT REQUIRED
            'amount' => $data['amount'],
            'AutoRedirect' => 'true',
            'lang' => $data['lang'],

        ]);
        Cache::put('payed', true, 10);
        $client->redirect_post(); // CREATE INPUTS HIDDEN, GENERATE A VALID HASH AND MAKE REDIRECT POST TO CMI

    }

    public function getCampaignsSpent($ownerId, array $stores_id)
    {
        $campaignsBidrthday = $this->smsService->countSms($stores_id);
        $campaignsCrossSellings =  SmsCrossSelling::whereIn('store_id', $stores_id)->where('status', 0)->count();
        $campaignMails = EmailCampaign::whereIn('store_id', $stores_id)->where('status', 0)->count();
        $smsPrice = config('constant.sms_price');
        $smsCrossSellingPrice = config('constant.sms_cross_selling');
        $mailPrice = config('constant.email_price');
        $total =  $campaignsBidrthday * $smsPrice +  $campaignsCrossSellings * $smsCrossSellingPrice + $campaignMails * $mailPrice;
        return $total - ($total * config('constant.exceptionnel_discount') );
    }

    public function callback($data)
    {
        $storeKey = config('app.CMI_STORE_KEY');
        $postParams = array();
        foreach ($data as $key => $value) {
            array_push($postParams, $key);
        }
        natcasesort($postParams);
        $hach = "";
        $hashval = "";
        foreach ($postParams as $param) {
            $paramValue = html_entity_decode(preg_replace("/\n$/", "", $data[$param]), ENT_QUOTES, 'UTF-8');

            $hach = $hach . "(!" . $param . "!:!" . $data[$param] . "!)";
            $escapedParamValue = str_replace("|", "\\|", str_replace("\\", "\\\\", $paramValue));

            $lowerParam = strtolower($param);
            if ($lowerParam != "hash" && $lowerParam != "encoding") {
                $hashval = $hashval . $escapedParamValue . "|";
            }
        }


        $escapedStoreKey = str_replace("|", "\\|", str_replace("\\", "\\\\", $storeKey));
        $hashval = $hashval . $escapedStoreKey;

        $calculatedHashValue = hash('sha512', $hashval);
        $actualHash = base64_encode(pack('H*', $calculatedHashValue));
        if(isset($data["HASH"])){
            $retrievedHash = $data["HASH"];
            if ($retrievedHash == $actualHash) {
                if ($data["ProcReturnCode"] == "00") {
                    return "ACTION=POSTAUTH";
                } else {
                    return "APPROVED";
                }
            } else {
                return "FAILURE";
            }
        }else{
            return 'request incomplete';
        }
        
    }

    public function setSession($emails, $countSmsBirthdayAndWelcome, $countSmsCrossSelling, $cmd)
    {
        $smsNormalPrice = config('constant.sms_price');
        $services = json_encode([
            'emails' => $emails,
            'email_price' => config('constant.email_price'),
            'sms_normal' => $countSmsBirthdayAndWelcome,
            'sms_normale_price' => $smsNormalPrice,
            'sms_advanced' => $countSmsCrossSelling,
            'sms_advanced_price' => config('constant.sms_cross_selling')
        ]);
        $total_ht = $emails * config('constant.email_price') + $countSmsBirthdayAndWelcome *  $smsNormalPrice + $countSmsCrossSelling * config('constant.sms_cross_selling');
        $total_ttc = $total_ht * (1 + config('constant.sherin_tva'));
        $historiqueInvoice = [
            'services' => $services,
            'cmd' => $cmd,
            'tva' => config('constant.sherin_tva'),
            'owner_id' =>  auth()->user()->id,
            'total_ht' => $total_ht,
            'total_ttc' =>  $total_ttc,
        ];
        $key = 'detail_invoice' . auth()->user()->id;
        session()->put($key, $historiqueInvoice);
        $this->cachePendingPayment(
            $cmd,
            auth()->user()->id,
            auth()->user()->stores->pluck('id')->toArray(),
            $historiqueInvoice
        );
    }
}
