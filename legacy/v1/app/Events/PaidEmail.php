<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PaidEmail
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $storesId;

    public function __construct(array $storesIds = [])
    {
        $this->storesId = $storesIds;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
