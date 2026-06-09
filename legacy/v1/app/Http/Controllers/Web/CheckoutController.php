<?php

namespace App\Http\Controllers\Web;

use App\Events\ActivateAccount;
use App\Events\DetailInvoice;
use App\Events\PaidEmail;
use App\Events\PaiedSms;
use App\Http\Controllers\Controller;
use App\Models\EmailCampaign;
use App\Models\SmsCrossSelling;
use App\Services\CheckoutService;
use App\Services\SmsService;
use App\Services\UserService;
use CMI\CmiClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{

    protected $checkoutService;
    protected $userService;
    public function __construct()
    {
        $this->checkoutService =  new CheckoutService;
        $this->userService =  new UserService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $commandeErrors = [];
        $commandeSuccess = [];
        if ($request->has('ErrCode')) {
            $commandeErrors = $request->all();
        } elseif ($request->has('ProcReturnCode') && $request->get('ProcReturnCode') === '00') {
            $commandeSuccess = $request->all();
        }
        $emails = EmailCampaign::whereIn('store_id', auth()->user()->stores->pluck('id')->toArray())->where('status', '=', 0)->get()->count();
        $countSmsBirthdayAndWelcome    = SmsService::countSms(auth()->user()->stores->pluck('id')->toArray());
        $countSmsCrossSelling          = SmsCrossSelling::whereIn('store_id', auth()->user()->stores->pluck('id')->toArray())->where('status', '=', 0)->get()->count();
        $cmd =  invoiceIdGenerator(auth()->user()->id);
        $this->checkoutService->setSession($emails, $countSmsBirthdayAndWelcome, $countSmsCrossSelling, $cmd);
        $owner = $this->userService->find(auth()->user()->id);
        return view('payement.checkout', compact('emails', 'owner', 'countSmsBirthdayAndWelcome', 'countSmsCrossSelling', 'cmd', 'commandeErrors', 'commandeSuccess'));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function detail()
    {
        return view('payement.checkout');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function process(Request $request)
    {
        $amount = $this->checkoutService->getCampaignsSpent(auth()->user()->id, auth()->user()->stores->pluck('id')->toArray());
        $amountWithTva = $amount * (1 + config('constant.nexa_tva'));
        $data   = [
            'cmd' => $request->cmd,
            'email' => auth()->user()->email,
            'firstname' => auth()->user()->firstname,
            'lastname' => auth()->user()->lastname,
            'address' => auth()->user()->address,
            'amount' => '' . $amountWithTva . '',
            'phone' => auth()->user()->phone,
            'lang' => app()->getLocale(),
        ];
        $key = 'detail_invoice' . auth()->user()->id;
        $invoiceDetail = session()->get($key, []);
        Event::dispatch(new DetailInvoice($invoiceDetail, null, null));
        $this->checkoutService->cachePendingPayment(
            $request->cmd,
            auth()->user()->id,
            auth()->user()->stores->pluck('id')->toArray(),
            $invoiceDetail
        );
        return $this->checkoutService->process($data);
    }

    public function success(Request $request)
    {
        Log::info("Now we are in success function", $request->all());
        $dataSend = $request->all();
        $dataSend['storekey'] = config('app.CMI_STORE_KEY');
        $hash = $request['HASH'];
        $client = new CmiClient($dataSend);
        $status = $client->hash_eq($request['HASH']);
        if ($status && $request->Response === 'Approved') {
            $context = $this->checkoutService->resolvePaymentContext($request->oid);
            Event::dispatch(new DetailInvoice($context['invoice_detail'], $request->oid, 1));
            Event::dispatch(new PaiedSms($context['store_ids']));
            Event::dispatch(new PaidEmail($context['store_ids']));
            Event::dispatch(new ActivateAccount($context['owner']->id, $context['store_ids']));
            $this->checkoutService->clearPendingPayment($request->oid);
            return redirect(route('owner.checkout-process-detail', $request));
        }
    }

    public function fail(Request $request)
    {
        Log::info("Now we are in fail function", $request->all());
        $context = $this->checkoutService->resolvePaymentContext($request->oid);
        $data['ErrCode'] =  $request->get('ErrCode');
        $data['oid'] =  $request->get('oid');
        $data['amount'] =  $request->get('amount');
        Event::dispatch(new DetailInvoice($context['invoice_detail'], $request->oid, 2));
        if ($request->oid) {
            $this->checkoutService->clearPendingPayment($request->oid);
        }
        return redirect(route('owner.checkout-process-detail', $data));
    }

    public function callBack(Request $request)
    {
        $storeKey = config('app.CMI_STORE_KEY');
        $postParams = array();
        foreach ($request->all() as $key => $value) {
            array_push($postParams, $key);
        }

        natcasesort($postParams);
        $hach = "";
        $hashval = "";
        foreach ($postParams as $param) {
            $paramValue = html_entity_decode(preg_replace("/\n$/", "", $request[$param]), ENT_QUOTES, 'UTF-8');

            $hach = $hach . "(!" . $param . "!:!" . $request[$param] . "!)";
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

        $retrievedHash = $request["HASH"];
        
        if ($retrievedHash == $actualHash) {
            if ($request["ProcReturnCode"] == "00") {
                echo "ACTION=POSTAUTH";
            } else {
                echo "APPROVED";
            }
        } else {
            echo "FAILURE";
        }
    }
}
