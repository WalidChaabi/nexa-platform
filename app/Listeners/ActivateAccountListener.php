<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ActivateAccountListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        User::where('id',$event->ownerId)->update([
            'is_active'=>1 
        ]); 
        User::whereIn('store_id',$event->stores_id)->update([
            'is_active'=>1 
        ]);
    }
}
