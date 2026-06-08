<?php

namespace App\Listeners;

use App\Events\PaiedSms;
use App\Services\CampaignService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class PaiedSmsListener
{
    protected $campaignService;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->campaignService = new CampaignService;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(PaiedSms $smsPayedEvent)
    {
        $this->campaignService->payCampaignsSms($smsPayedEvent->storesId);
    }
}