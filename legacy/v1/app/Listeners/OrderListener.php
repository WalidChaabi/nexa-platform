<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\OrderEvent;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\EmailCampaign;
use Mail;
use Config;
class OrderListener
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
    public function handle($event)
    {
        
        
        \Config::set('mail.from.name', $event->store->name);
        
       //facture/details de votre commande
        Mail::send('emails.order', 
         [
            "store"=>$event->store,
            "client"=>$event->client,
            'invoice'=>$event->invoice

            ], function ($message) use($event) {
            $message->to($event->client->email);
            $message->subject(__('facture #').$event->invoice->id.__(' /Details de votre commande'));
        });
        
        EmailCampaign::create([
            'store_id'=>$event->store->id,
            'invoice_id'  =>$event->invoice->id ,
            'status'    =>1,
        ]);
        
    
    }
}
