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
        $this->historiquePaiementService->create($event->invoiceDetail, $event->cmd, $event->status);

        $ownerId = null;
        if (is_array($event->invoiceDetail) && isset($event->invoiceDetail['owner_id'])) {
            $ownerId = $event->invoiceDetail['owner_id'];
        } elseif (auth()->check()) {
            $ownerId = auth()->user()->id;
        }

        if ($ownerId) {
            session()->forget('detail_invoice' . $ownerId);
        }
    }
}
