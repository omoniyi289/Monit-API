<?php

namespace App\Listeners;

use App\Events\VeloxPaymentRequestGenerated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\VeloxPaymentApprovalMail;
use Mail;

class SendVeloxPaymentApprovalMail implements ShouldQueue
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
    public function handle(VeloxPaymentRequestGenerated $event)
    {
        $emailData = $event->getData();
        $data = $emailData["data"];
       // $selected_customer = $emailData["selected_customer"];
        $user = $emailData["user"];
        Mail::to($user['email'])->send(new VeloxPaymentApprovalMail($data,$user));

     }
}
