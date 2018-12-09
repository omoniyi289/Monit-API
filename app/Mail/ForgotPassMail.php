<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Sichikawa\LaravelSendgridDriver\SendGrid;

class ForgotPassMail extends Mailable
{
    use Queueable, SerializesModels;
    use SendGrid;
    public $data;

    /**
     * Create a new message instance.
     *
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Station Manager Support";
        return $this->view('email.forgotpass')
            ->from("support@e360africa.com", "Stationmanager")
            ->replyTo(  "support@e360africa.com", "Stationmanager")
            ->subject($subject)
            ->with([
                'fullname' => $this->data["fullname"],
                'new_pass' => $this->data['new_pass'],
            ]);
    }
}
