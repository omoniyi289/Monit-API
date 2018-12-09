<?php

namespace App\Listeners;

use App\Events\COPSGenerated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\COPSReportMail;
use Mail;
use App\Models\COPS;
use App\Models\UsersInStations_Details;
use App\Station;
use PDF;
use Storage;

class SendCOPSMail implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public static $final_pdfs= array();
    public static $full_data =array();
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  COPSGenerated  $event
     * @return void
     */
    public function handle(COPSGenerated $event)
    {
        
            $emailData = $event->getData();
            $data = $emailData["receiver_data"];
            $date = $emailData["date"];
                    //generate pdf
            $name = $data[0]->company['name'];
            $company_id = $data[0]->company['id'];
            SendCOPSMail::$full_data = $data;
            $finale= SendCOPSMail::$full_data;
            $pdf = PDF::setPaper('a4', 'portrait');
              ///delete previous
            Storage::delete('app/cops_reports/'.$name.'.pdf');
               //store new
            $pdf->loadView('cops',  compact('finale'))->save(storage_path('app/cops_reports/'.$name.'.pdf'));
            SendCOPSMail::$final_pdfs[$name] =  'app/cops_reports/'.$name.'.pdf';

            //send mail
            $users_in_stations_details =  UsersInStations_Details::where('companyid', $company_id)->where('hasaccess', 1)->where('notification_module', 'Commercial Online Pricing Report')->where('NotificationAllowed', 1)->distinct()->get(['firstname','companyname', 'station_name', 'email']);
              
            $grouped_users= array();
            $grouped_users= $this->sortbyUserInStation($users_in_stations_details);
              
            foreach ($grouped_users as $value) {
                 $mail_data = [
                   'receiver_data'=> $value,
                    'date' => $date
                  ]; 

               if(isset($value[0]['email'])){
                    Mail::to([$value[0]['email'], "support@e360africa.com", "omoniyi.o@e360africa.com"])->send(new COPSReportMail($mail_data, $value));
                }
               // Mail::to("omoniyi.o@e360africa.com")->send(new COPSReportMail($mail_data, $value ));
              }
    }

    private function sortbyUserInStation($users_in_stations_details){
      $grouped_users= array();
      $grouped_filenames= array();
      foreach ($users_in_stations_details as $value) {
        if($value['email'] !== null){
          if(!isset($grouped_users[$value['email']])){
            if(isset(SendCOPSMail::$final_pdfs[$value['companyname']])){
            $grouped_users[$value['email']] = array();
            $grouped_filenames[$value['email']] = array();
              array_push($grouped_users[$value['email']], ['email' => $value['email'], 'firstname' => $value['firstname'], 'companyname' => $value['companyname'], 'pdf' => SendCOPSMail::$final_pdfs[$value['companyname']]]);
           }
          }else{    
            if(isset(SendCOPSMail::$final_pdfs[$value['companyname']])){
              array_push($grouped_users[$value['email']], ['email' => $value['email'], 'firstname' => $value['firstname'],'companyname' => $value['companyname'], 'pdf' => SendCOPSMail::$final_pdfs[$value['companyname']]]);
          }
          }
          }
    }
    return $grouped_users;
  
}
   



}
