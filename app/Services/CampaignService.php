<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\CampaignDetail;
use App\Models\CampaignHistorique;
use App\Models\Customer;
use App\Models\EmailCampaign;
use App\Models\Message;
use App\Models\MessageCampaign;
use App\Models\MessageCustomer;
use App\Models\SmsCrossSelling;
use Illuminate\Support\Facades\Log;
use App\Models\Store;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CampaignService
{
    public function getAll()
    {
      return Campaign::orderBy('id','DESC')->get();
    }
    public function getAdminCampaignDetail()
    {
      return CampaignDetail::latest()->get();
    }
    public function getOwnerCampaignList()
    {
      return CampaignDetail::whereIn('store_id',auth()->user()->stores)->get();
    }
    public function getOwnerCampaignDetail($type)
    {
      return MessageCustomer::where('messages_customers.type',$type)->paginate(10);
    }
    public function getCustomerByName($type,$name)
    {
        return $messagesCustomers = MessageCustomer::join('customers','messages_customers.customer_id','=','customers.id')
            ->where([['messages_customers.type',$type],['customers.firstname',$name]])
            ->get();
    }


    public function getCampaignHistoriqueByCodeAndCustomerId(string $code, string $customerId)
    {
        $campaignHistory = CampaignHistorique::where('code_red', '=', $code)->where('customer_id', '=', $customerId)->where('expired_code', '=', 0)->first();
        if ($campaignHistory) {

            if (date('Y-m-d', strtotime($campaignHistory->start_date . ' + 8 days')) >= date('Y-m-d')) {
                return response()->json(['success' => 1, 'code' => '0.1']);
            } else {
                return response()->json(['success' => 0, 'message' => 'Vous pouvez pas utiliser ce code de reduction car il est expiré!']);
            }
        } else {
            return response()->json(['success' => 0, 'message' => 'Code invalide']);
        }
    }

    public function createCampaign($data)
    {
        if (count($data)) {
            //create historique campaign that will contain the store id the users ids and
            foreach ($data as $els) {

                $campaignHistorique = [];
                $campaignsHistorique = [];
                $customers = $els['customers'];
                foreach ($customers as $customer) {

                    $campaignHist = CampaignHistorique::where('customer_id', '=', $customer->id)->where('status', '=', 0)->first();
                    if (!$campaignHist) {
                        $campaignHistorique['camp_id']     = 1;
                        $campaignHistorique['type']        = 1; //bulk sending
                        $campaignHistorique['store_id']    = $els['store_id'];
                        $campaignHistorique['customer_id'] = $customer->id;
                        $campaignHistorique['phone']       = $customer->phone;
                        $campaignHistorique['start_date']  = $els['start_date'];
                        $campaignHistorique['code_red']    = $this->generateCode(6);
                        CampaignHistorique::Create($campaignHistorique);
                    }
                }
            }
        }
    }

    public function generateCode($length = 6)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    private function formattMessage($nameCar = '(Prénom)', $name = '', $codeCar = '******', $code = '', $day = '', $senderId)
    {

        $message = str_replace($nameCar, $name, config('messages.' . $senderId . '.' . $day));
        Log::info('messages.' . $senderId . '.' . $day);
        Log::info('config message ' . config('messages.' . $senderId . '.' . $day));

        $message = str_replace($codeCar, $code, $message);

        return $message;
    }

    public function singleCampaign($data)
    {
        $campHistory =  CampaignHistorique::create([
            'camp_id'     => isset($data['camp_id']) ? $data['camp_id']  :1,
            'store_id'    => $data['store_id'],
            'customer_id' => isset($data['customer_id']) ? $data['customer_id']:null, //nullable
            'phone'       => $data['phone'],
            'start_date'  => Carbon::now(),
        ]);
        Log::info([$data['message']]);
        $this->sendSms($data['phone'], $data['message'], $data['sender']);
        $campHistory->status = 1;
        $campHistory->save();
    }

    public function sendSms($num = "0675295551", $texte = "hello hamza today ", $emetteur = "MORINO")
    {
        return (new BulkSmsClient())->send($num, $texte, $emetteur);
    }


    public function getTodayHistoriques()
    {

        return CampaignHistorique::where('status', '=', 0)->where('camp_id', '=', 1)->whereDate('start_date', Carbon::today())->get();
    }   

    public function getLastDayFinishedCampaigns()
    {
        $result = CampaignHistorique::where('status', '=', 3)->where('camp_id', '=', 1)->get();
        foreach ($result as $el)
            $el->update([
                'status' => 4
            ]);
    }

    public function getLastDayCampaigns()
    {
        return  CampaignHistorique::where('status', '=', 2)->where('camp_id', '=', 1)->get();
    }

    public function launchLastDayCampaigns($data)
    {
        $phones = '';
        $count = count($data);
        $i = 0;
        foreach ($data as $el) {
            $el->update(['status' => 3]);
            $senderId = Store::find($el['store_id'])->sender_id;
            $this->sendSms($el->phone, $this->formattMessage($nameCar = '(Prénom)', 
            ($el->customer->lastname) 
                ? $el->customer->lastname 
                : $el->customer->firstname, $codeCar = '******', $el->code_red, $day = 'thirdDay', $senderId), $senderId);
        }

    }

    public function getSecondeDayCampaigns()
    {
        return  CampaignHistorique::where('status', '=', 1)->where('camp_id', '=', 1)->get();
    }

    public function launchSecondeDayCampaigns($data)
    {
        $phones = '';
        $count = count($data);
        $i = 0;
        foreach ($data as $el) {
            $el->update(['status' => 2]);
            $senderId = Store::find($el['store_id'])->sender_id;
            // if($senderId !='MORINO')
            // $this->sendSms($el->phone, $this->formattMessage($nameCar = '(Prénom)', ($el->customer->lastname) ? $el->customer->lastname : $el->customer->firstname, $codeCar = '******', $el->code_red, $day = 'secondeDay', $senderId), $senderId);
        }
        //send sms to this user
    }

    public function getFirstDayCampaigns()
    {
        return  CampaignHistorique::where('status', '=', 0)->where('camp_id', '=', 1)->get();
    }
    public function launchFirstDayCampaigns($data)
    {

        foreach ($data as $el) {
            $el->update(['status' => 1]);
            $senderId = Store::find($el['store_id'])->sender_id;
            // if($senderId !='MORINO')
                // $this->sendSms($el->phone, $this->formattMessage($nameCar = '(Prénom)', ($el->customer->lastname) ? $el->customer->lastname : $el->customer->firstname, $codeCar = '******', $el->code_red, $day = 'firstDay', $senderId), $senderId);
        }
    }

    public function payCampaignsSms(array $storesId)
    {
        CampaignDetail::whereIn('store_id', $storesId)->where('payed', '=', 0)->update(['payed' => 1]);
    }

    public function payCampaignsEmail(array $storesId)
    {
        EmailCampaign::whereIn('store_id', $storesId)->where('status', '=', 0)->update(['status' => 1]);
    }

    public function customResetCampaign($storeId,$number,$type){
        // dd($number);
        $count = MessageCustomer::
            where([
                ['messages_customers.store_id', $storeId],
                ['messages_customers.type', '=', $type],
                ['messages_customers.payed', '=', '0']
            ])
            ->select( DB::raw('count(*) as count'))
            ->first();

        $countValue = $count->count;

        // dd($countValue);
     
        if ($number>=$countValue) {
            $errorMessage = " Le chiffre entrée est plus grand que le crédit de ce magasin.";
            return back()->withErrors($errorMessage);
        }
        else{
            $messages = MessageCustomer::
                where([
                    ['messages_customers.store_id', $storeId],
                    ['messages_customers.type', '=', $type],                
                    ['messages_customers.payed', '=', '0']
                ])
                ->take($number)
                ->update(['messages_customers.payed' => 1]);
            return $messages;
        }
        
        // $XX = DB::table('campaign_details')
        // ->join('campaigns', 'campaign_details.campaign_id', '=', 'campaigns.id')
        // ->join('message_campaigns', 'campaign_details.id', '=', 'message_campaigns.campaign_details_id')
        // ->join('messages', 'message_campaigns.message_id', '=', 'messages.id')
        // ->where([
        //     ['campaign_details.store_id', '=', $storeId],
        //     ['campaigns.type', '=', $type],
        //     ['messages.payed', '=', '0']
        //     ])
        //     ->value(DB::raw('count(*)as count'));
            
        // if ($number>=$XX) {
        //     $errorMessage = " Le chiffre entrée est plus grand que le crédit de ce magasin.";
        //     return back()->withErrors($errorMessage);
        // }
        // else{
        //     $campaignDetails = DB::table('campaign_details')
        //     ->join('campaigns', 'campaign_details.campaign_id', '=', 'campaigns.id')
        //     ->join('message_campaigns', 'campaign_details.id', '=', 'message_campaigns.campaign_details_id')
        //     ->join('messages', 'message_campaigns.message_id', '=', 'messages.id')
        //     ->where([
        //         ['campaign_details.store_id', '=', $storeId],
        //         ['campaigns.type', '=', $type],
        //         ['messages.payed', '=', '0']
        //         ])
        //         ->take($number)
        //         ->update(['messages.payed' => 1]);
        //     return $campaignDetails;
        // }
    }
            
    public function payAllCampaign($storesId){
        
        $types = MessageCustomer::where('messages_customers.payed', '=', '0')
            ->when(is_array($storesId), function ($query) use ($storesId) {
                return $query->whereIn('messages_customers.store_id', $storesId);
            }, function ($query) use ($storesId) {
                return $query->where('messages_customers.store_id', $storesId);
            })
            ->select('messages_customers.type')
            ->groupBy('messages_customers.type')
            ->get();
            foreach( $types as  $type){
                $count = MessageCustomer::where([
                    ['messages_customers.payed', '=', '0'],
                    ['messages_customers.type','=',$type->type]
                ])
                ->when(is_array($storesId), function ($query) use ($storesId) {
                    return $query->whereIn('messages_customers.store_id', $storesId);
                }, function ($query) use ($storesId) {
                    return $query->where('messages_customers.store_id', $storesId);
                })
                ->update(['messages_customers.payed' => 1]); 
                $details[] = [$type->type, $count];
            }
        return $details ;
        // $details =[];
        // $types = DB::table('campaign_details')
        // ->join('campaigns', 'campaign_details.campaign_id', '=', 'campaigns.id')
        // ->join('message_campaigns', 'campaign_details.id', '=', 'message_campaigns.campaign_details_id')
        // ->join('messages', 'message_campaigns.message_id', '=', 'messages.id')
        // ->when(is_array($storesId), function ($query) use ($storesId) {
        //     return $query->whereIn('campaign_details.store_id', $storesId);
        // }, function ($query) use ($storesId) {
        //     return $query->where('campaign_details.store_id', $storesId);
        // })
        // ->where('messages.payed', '=', '0')
        // ->select('campaigns.type')
        // ->groupBy('campaigns.type')
        // ->get();
        // foreach( $types as  $type){
        //     $count = DB::table('campaign_details')
        //     ->join('campaigns', 'campaign_details.campaign_id', '=', 'campaigns.id')
        //     ->join('message_campaigns', 'campaign_details.id', '=', 'message_campaigns.campaign_details_id')
        //     ->join('messages', 'message_campaigns.message_id', '=', 'messages.id')
        //     ->when(is_array($storesId), function ($query) use ($storesId) {
        //         return $query->whereIn('campaign_details.store_id', $storesId);
        //     }, function ($query) use ($storesId) {
        //         return $query->where('campaign_details.store_id', $storesId);
        //     })
        //     ->where([['messages.payed', '=', '0'],['campaigns.type','=',$type->type]])
        //     ->update(['messages.payed' => 1]); 
        //     $details[] = [$type->type, $count];
        // }
        // return $details ;
    }
    // public function customResetCampaign($storeId,$number,$type){

        // $campaignDetails = DB::table('campaign_details')
        // ->join('campaigns', 'campaign_details.campaign_id', '=', 'campaigns.id')
        // ->where([
        //     ['campaign_details.store_id', '=', $storeId],
        //     ['campaigns.type', '=', $type]
        //     ])
        // ->get();
        // // dd($number);
        // // $messages = collect();
        // $numUpdated = 0;
        // $i = 0;

        // foreach($campaignDetails as $campaignDetail){
        //     if ($i >= $number) { // check if iteration counter exceeds limit
        //         break; // break out of loop
        //     }
        //     $numUpdatedPerCampaign = DB::table('message_campaigns')
        //     ->join('messages', 'message_campaigns.message_id', '=', 'messages.id')
        //     ->where([
        //         ['message_campaigns.campaign_details_id', '=', $campaignDetail->id],
        //         ['messages.payed', '=', '0']
        //         ])
        //     ->update(['messages.payed' => 1]); 
        //     // Log::debug("Updated $message messages for campaignDetail ID {$campaignDetail->id}");
        //     $numUpdated += $numUpdatedPerCampaign;
        //     $i++;
        // }
        // // dd($numUpdated);
        // return $numUpdated;
    
    // }

    public function AddCredit($store,$number){
        for ($i=0; $i < $number ; $i++) { 
            $message = MessageCustomer::create([
                'store_id'=>$store,
                'customer_id'=>0,
                'message'=>'credit supplementaire',
                'payed'=>0,
                'type'=>'credit-sup'
            ]);
        }
    }
    public function getReduction($text){
        $pattern = '/\[code\s+(\d+)%\]/'; // regular expression pattern to match the dynamic value

        if (preg_match($pattern, $text, $matches)) {
           return $reduction = $matches[1];
        } else {
            return 0 ;
        }
    }
    public function Message($text,$first_name,$last_name,$code){

        // $text = str_replace(['[FirstName]','[LastName]','[code]'] ,[$first_name,$last_name,$code.' '.$reduction.' pour cent'], $text);
        $text = str_replace(['[FirstName]','[LastName]','[code]'] ,[$first_name,$last_name,$code], $text);
        return $text;
    }
    public function MessageRdv($text,$first_name,$last_name,$code,$date){

        // $text = str_replace(['[FirstName]','[LastName]','[code]'] ,[$first_name,$last_name,$code.' '.$reduction.' pour cent'], $text);
        $text = str_replace(['[FirstName]','[LastName]','[code]','[date]'] ,[$first_name,$last_name,$code,$date], $text);
        return $text;
    }
    public function getDetailsMessages($storesId)
    {
        $campaignDetails = CampaignDetail::select('campaigns.type', 'campaigns.name', 'campaign_details.*')
            ->join('campaigns', 'campaigns.id', '=', 'campaign_details.campaign_id')
            ->when(is_array($storesId), function ($query) use ($storesId) {
                return $query->whereIn('campaign_details.store_id', $storesId);
            }, function ($query) use ($storesId) {
                return $query->where('campaign_details.store_id', $storesId);
            })
            ->get();
        $campaignsCount = [];
    
        foreach ($campaignDetails as $campaignDetail) {

            $messages = DB::table('message_campaigns')
            ->join('messages', 'message_campaigns.message_id', '=', 'messages.id')
            ->where([
                ['message_campaigns.campaign_details_id', '=', $campaignDetail->id],
                ['messages.payed', '=', '0']
                ])
            ->select('message_id', DB::raw('count(*) as count'))
            ->groupBy('message_id')
            ->get();
            $campaignType = $campaignDetail->type;
            $campaignName = $campaignDetail->name;
            if (!isset($campaignsCount[$campaignType])) {
                $campaignsCount[$campaignType] = [$campaignName,0];
            }
            foreach ($messages as $message) {
                $messageCount = $message->count;    
                $campaignsCount[$campaignType][1] += $messageCount;
            }
        }
        $result = [];
        foreach ($campaignsCount as $key => $value) {
            $result[$key] = $value;
        }
        return $result;
    }
}