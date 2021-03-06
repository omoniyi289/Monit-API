<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Sichikawa\LaravelSendgridDriver\SendGrid;

class COPSReportMail extends Mailable
{
    use Queueable, SerializesModels;
    use SendGrid;
    public $data;
    public $pdf;
    public $filenames;
    /**
     * Create a new message instance.
     *
     * @param $data
     */
    public function __construct($data, $pdf)
    {
        $this->data = $data;
        $this->pdf = $pdf;
       // $this->filenames = $filenames;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {    $date = $this->data['date'];

        $subject = "E360 Commercial Online Pricing Report";
        $mail_build = $this->view('email.cops_report')
            ->from("support@e360africa.com", "E360 Africa")
            ->replyTo(  "support@e360africa.com", "E360 Africa")
            ->bcc(  "support@e360africa.com", "E360 Support")
            ->subject($subject)
            ->with([
                 'date' => $date,
                 'company' => $this->pdf[0]['companyname']

            ]);
   
           foreach ($this->pdf as $value) {
            $file = storage_path('app/cops_reports/'.$value['companyname'].'.pdf');

            $mail_build = $mail_build->attach($file, [
                'as' => $value['companyname'].'_'.$date.'.pdf',
                'mime' => 'application/pdf'
            ]);
        }
        return $mail_build;
    }
}
