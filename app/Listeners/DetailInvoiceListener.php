<?php

namespace App\Listeners;

use App\Models\HistoriquePaiement;
use App\Services\HistoriquePaiementService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DetailInvoiceListener
{
    protected $historiquePaiementService;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->historiquePaiementService = new HistoriquePaiementService;
        
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $this->historiquePaiementService->create($event->invoiceDetail,$event->cmd, $event->status);
        $key = 'detail_invoice' . auth()->user()->id;
        session()->forget($key);
    }
}
