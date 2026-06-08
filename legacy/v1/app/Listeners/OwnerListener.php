<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\OwnerEvent;
use Mail;

class OwnerListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(OwnerEvent $event)
    {
        Mail::send('emails.owner', ["owner"=>$event->owner,'password'=>$event->password], function ($message) use($event) {
            $message->to($event->owner->email);
            $message->subject('Sherin:Configuration du Compte.');
        });
    }
}
