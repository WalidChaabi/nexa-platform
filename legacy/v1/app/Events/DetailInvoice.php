<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class DetailInvoice
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $invoiceDetail;
    public $cmd;
    public $status;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( $invoiceDetail,$oid =null,$status=0)
    {
        $this->invoiceDetail = $invoiceDetail;
        $this->cmd = $oid;
        $this->status = $status;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
