<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Sichikawa\LaravelSendgridDriver\SendGrid;

class GoodsInTransitMail extends Mailable
{
    use Queueable, SerializesModels;
    use SendGrid;
    public $data;
    public $station;

    /**
     * Create a new message instance.
     *
     * @param $data
     */
    public function __construct($station,$data)
    {
        $this->data = $data;
        $this->station = $station;
       
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Goods in Transit Notification";
        return $this->view('email.goods_in_transit')
            ->from("stationmanager@e360africa.com", "Station Manager 2.0")
            ->replyTo(  "stationmanager@e360africa.com", "Station Manager 2.0")
            ->subject($subject)
            ->with([
              
                'station' => $this->station,
                 'data' => $this->data,
                
            ]);
    }
}
