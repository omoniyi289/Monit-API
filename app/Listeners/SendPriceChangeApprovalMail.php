<?php

namespace App\Listeners;

use App\Events\PriceChangeApprovalGenerated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\PriceChangeMail;
use Mail;

class SendPriceChangeApprovalMail implements ShouldQueue
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
     * @param  PriceChangeApprovalGenerated  $event
     * @return void
     */
    public function handle(PriceChangeApprovalGenerated $event)
    {
        $emailData = $event->getData();
        $station = $emailData["station"];
        $user = $emailData["user"];
        $last_updated_by = $emailData["last_updated_by"];
        $product_change_result = $emailData["product_change_result"];

        Mail::to($user['email'])->send(new PriceChangeMail($station,$user,$last_updated_by, $product_change_result ));

     }
}
