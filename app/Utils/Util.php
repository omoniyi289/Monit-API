<?php
/**
 * Created by PhpStorm.
 * User: e360
 * Date: 1/18/18
 * Time: 12:48 AM
 */

namespace App;


use Mail;

class Util
{
    public static function send_registration_mail($fullname, $email, $verfication_code)
    {
        $subject = "ACCOUNT VERIFICATION";
        Mail::send('email.verify', [
            'Full name' => $fullname,
            'verification_code' => $verfication_code
        ], function ($mail) use ($email, $fullname, $subject) {
            $mail->from("stationmanager2.0@gmail.com", "Station Manager");
            $mail->to($email, $fullname);
            $mail->subject($subject);
        });
    }
}