<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Sichikawa\LaravelSendgridDriver\SendGrid;

class RegistrationMail extends Mailable
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
        return $this->view('email.verify2')
            ->from("support@e360africa.com", "Stationmanager")
            ->replyTo(  "support@e360africa.com", "Stationmanager")
            ->bcc('support@e360africa.com', "E360 Support")
            ->subject($subject)
            ->with([
                'fullname' => $this->data["fullname"],
                'verification_code' => $this->data['verification_code'],
            ]);
    }
}
