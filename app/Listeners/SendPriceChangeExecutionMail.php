<?php

namespace App\Listeners;

use App\Events\PriceChangeExecutionGenerated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\PriceChangeExecuteMail;
use Mail;

class SendPriceChangeExecutionMail
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
     * @param  PriceChangeExecutionGenerated  $event
     * @return void
     */
    public function handle(PriceChangeExecutionGenerated $event)
    {
        $emailData = $event->getData();
        $station = $emailData["station"];
        $user = $emailData["user"];
        $last_updated_by = $emailData["last_updated_by"];
        $product_change_result = $emailData["product_change_result"];

        Mail::to($user['email'])->send(new PriceChangeExecuteMail($station,$user,$last_updated_by, $product_change_result ));

     }
}
