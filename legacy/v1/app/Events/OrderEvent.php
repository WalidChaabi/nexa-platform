<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Store;
class OrderEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $client;
    public $store; 
    public $invoice; 
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Customer $client,Invoice $invoice,Store $store)
    {
        $this->client=$client;
        $this->invoice=$invoice;
        $this->store=$store;
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
