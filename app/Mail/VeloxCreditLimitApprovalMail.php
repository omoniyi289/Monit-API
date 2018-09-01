<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Sichikawa\LaravelSendgridDriver\SendGrid;

class VeloxCreditLimitApprovalMail extends Mailable
{
    use Queueable, SerializesModels;
    use SendGrid;
    public $data;

    /**
     * Create a new message instance.
     *
     * @param $data
     */
    public function __construct($data, $user)
    {
        $this->data = $data;
        $this->user = $user;
       // $this->selected_customer = $selected_customer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Velox Customer Credit Limit Approval Notification";
        return $this->view('email.velox_customer_creditlimit_approval_notification')
            ->from("sm@e360africa.com", "Station Manager 2.0")
            ->replyTo(  "sm@e360africa.com", "Station Manager 2.0")
            ->subject($subject)
            ->with([
                'user' => $this->user,
                  'data' => $this->data
            ]);
    }
}
