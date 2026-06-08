<?php

namespace App\Listeners;

use App\Services\CampaignService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PaidEmailListener
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
    public function handle($emailPayedEvent)
    {
        $this->campaignService->payCampaignsEmail($emailPayedEvent->storesId);
    }
}
