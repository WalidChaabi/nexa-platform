<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ActivateAccount
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $ownerId;
    public $stores_id;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($ownerId,$stores_id)
    {
        $this->ownerId = $ownerId;
        $this->stores_id = $stores_id;
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
