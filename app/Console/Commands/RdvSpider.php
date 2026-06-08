<?php

namespace App\Console\Commands;

use App\Services\CampaignService;
use App\Services\RdvService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class RdvSpider extends Command
{
  
    protected $signature = 'rdv:spider';

    protected $description = 'Command description';
    
    protected $rdvService;
    protected $campaignService;


    public function __construct()
    {
        parent::__construct();
        $this->rdvService = new RdvService;
        $this->campaignService = new CampaignService;
    }


    public function handle()
    {
        Log::info('start of rdv 30 min command');
        Log::info( [now(),now()->addMinute(5)]);
        $rdvs =  $this->rdvService->get30MinBefore();
        dump($rdvs);
        Log::info([$rdvs]);
        foreach ($rdvs as $rdv) {
            $data = $this->data($rdv, 'rdv-3');
            $this->campaignService->singleCampaign($data);
            $rdv = $rdv->toArray();
            // $rdv['status'] = 1;
            $this->rdvService->update($rdv);
        }
        Log::info('end of rdv 30 min command');

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

    private function message(string $senderId, string $rdv, $date)
    {
        return  str_replace('******', $date, config('messages.' . $senderId . '.' . $rdv));
    }
}
