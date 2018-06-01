<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Sichikawa\LaravelSendgridDriver\SendGrid;

class PriceChangeMail extends Mailable
{
    use Queueable, SerializesModels;
    use SendGrid;
    public $data;

    /**
     * Create a new message instance.
     *
     * @param $data
     */
    public function __construct($station, $user, $creator_name,$data)
    {
        $this->data = $data;
        $this->user = $user;
        $this->station = $station;
        $this->creator_name=$creator_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Price Change Notification";
        return $this->view('email.price_change_notification')
            ->from("support@e360africa.com", "Station Manager 2.0")
            ->replyTo(  "support@e360africa.com", "Station Manager 2.0")
            ->subject($subject)
            ->with([
                'user' => $this->user,
                'station' => $this->station['name'],
                 'data' => $this->data,
                 'creator_name'=>$this->creator_name,
            ]);
    }
}
