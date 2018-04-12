<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Sichikawa\LaravelSendgridDriver\SendGrid;

class FuelSupplyMail extends Mailable
{
    use Queueable, SerializesModels;
    use SendGrid;
    public $data;

    /**
     * Create a new message instance.
     *
     * @param $data
     */
    public function __construct($station, $user, $creator_name,$data, $request_code)
    {
        $this->data = $data;
        $this->user = $user;
        $this->station = $station;
        $this->creator_name=$creator_name;
        $this->request_code = $request_code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Fuel Supply Notification";
        return $this->view('email.fuel_supply_notification')
            ->from("stationmanager@e360africa.com", "Station Manager 2.0")
            ->replyTo(  "stationmanager@e360africa.com", "Station Manager 2.0")
            ->subject($subject)
            ->with([
                'user' => $this->user,
                'station' => $this->station['name'],
                 'data' => $this->data,
                 'creator_name'=>$this->creator_name,
                 'request_code' => $this->request_code,
            ]);
    }
}
