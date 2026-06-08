<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\EmployEvent;
use Mail;
class EmployListener
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
    public function handle(EmployEvent $event)
    {
        
        Mail::send('emails.employ', 
         [
            "employ"=>$event->employ,
            "store"=>$event->store,
            'password'=>$event->password

            ], function ($message) use($event) {
            $message->to($event->employ->email);
            $message->subject('Sherin:Configuration du Compte Employé.');
        });
    }
}
