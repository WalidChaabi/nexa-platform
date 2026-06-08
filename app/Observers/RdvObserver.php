<?php

namespace App\Observers;

use App\Models\Rdv;
use App\Services\CampaignService;
use App\Services\RdvInstantaneService;
use App\Services\UpdateRdvService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class RdvObserver
{
    protected $campaignService;
    protected $rdvInstantaneService;
    protected $updateRdvService;

    public function __construct()
    {
        $this->rdvInstantaneService = new RdvInstantaneService;
        $this->updateRdvService = new UpdateRdvService;
        $this->campaignService = new CampaignService;
    }

    public function created(Rdv $rdv)
    {
        $data = $this->data2($rdv);
        $DetailsRendezVous = $this->rdvInstantaneService->SmsRdvInstantane($data['id'],$data['store_id'], $data['customer_id'],$data['start_date']);        
        Log::info('rdv instantane message campaigne input :',$data);
        Log::info('rdv instantane message campaigne output :',$DetailsRendezVous);       
        // $data = $this->data($rdv, 'rdv-1');
        // Log::info("hello hello ",$data);
        // $this->campaignService->singleCampaign($data);
        // Log::info('success');
    }

    public function updated(Rdv $rdv)
    {
        if ($rdv->status == 0) {
            $data = $this->data2($rdv);
            $DetailsRendezVous = $this->updateRdvService->SmsUpdateRdv($data['id'],$data['store_id'], $data['customer_id'],$data['start_date']);

            Log::info('rdv updated message campaigne input :',$data);
            Log::info('rdv updated message campaigne output :',$DetailsRendezVous);
            // Log::info("rdv updated message campaigne");
            // $data = $this->data($rdv, 'rdv-2');
            // $this->campaignService->singleCampaign($data);
            // Log::info('success');
        }
    }

    public function deleted(Rdv $rdv)
    {
        //
    }

    public function restored(Rdv $rdv)
    {
        //
    }

    public function forceDeleted(Rdv $rdv)
    {
        //
    }

    private function message(string $senderId, string $rdv, $date)
    {
        return  str_replace('******', $date, config('messages.' . $senderId . '.' . $rdv));
    }

    private function data2($rdv)
    {
        $data = [
            'id'          =>$rdv->id,
            'store_id'    => $rdv->customer->store_id,
            'customer_id' => $rdv->customer_id,
            'start_date'  => Carbon::parse($rdv->start)->format('Y-m-d H:i'),
        ];
        return $data;
    }
    
    private function data($rdv, $rdvNumber)
    {
        $senderId = $rdv->customer->store->sender_id;
        $date =  Carbon::parse($rdv->start)->format('Y-m-d H:i');
        $message = $this->message($senderId, $rdvNumber, $date);
        $data = [
            'store_id'    => $rdv->customer->store_id,
            'customer_id' => $rdv->customer_id, //nullable
            'phone'       => $rdv->customer->phone,
            'start_date'  => Carbon::now(),
            'message'  => $message,
            'sender'  => $rdv->customer->store->sender_id,
        ];
        return $data;
    }
}
