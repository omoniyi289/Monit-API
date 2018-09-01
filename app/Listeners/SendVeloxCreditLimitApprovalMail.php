<?php

namespace App\Listeners;

use App\Events\VeloxCreditLimitRequestGenerated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\VeloxCreditLimitApprovalMail;
use Mail;

class SendVeloxCreditLimitApprovalMail implements ShouldQueue
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
    public function handle(VeloxCreditLimitRequestGenerated $event)
    {
        $emailData = $event->getData();
        $data = $emailData["data"];
       // $selected_customer = $emailData["selected_customer"];
        $user = $emailData["user"];
        Mail::to($user['email'])->send(new VeloxCreditLimitApprovalMail($data,$user));

     }
}
