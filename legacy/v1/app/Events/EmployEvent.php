<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\User;
use App\models\Store;
class EmployEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $employ;
    public $password;
    public $store;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $employ,Store $store,$password)
    {
        $this->employ=$employ;
        $this->store=$store;
        $this->password=$password;
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
