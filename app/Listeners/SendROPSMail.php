<?php

namespace App\Listeners;

use App\Events\ROPSGenerated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\ROPSReportMail;
use Mail;
use App\Models\ROPS;
use App\Models\UsersInStations_Details;
use App\Station;
use PDF;
use Storage;

class SendROPSMail implements ShouldQueue
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
     * @param  ROPSGenerated  $event
     * @return void
     */
    public function handle(ROPSGenerated $event)
    {
        
            $emailData = $event->getData();
            $data = $emailData["receiver_data"];
            $date = $emailData["date"];
                    //generate pdf
            $name = $data->station['name'];
            SendROPSMail::$full_data = $data;
            $finale= SendROPSMail::$full_data;
            $pdf = PDF::setPaper('a4', 'landscape');
              ///delete previous
            Storage::delete('app/rops_reports/'.$name.'.pdf');
               //store new
            $pdf->loadView('rops',  compact('finale'))->save(storage_path('app/rops_reports/'.$name.'.pdf'));
            SendROPSMail::$final_pdfs[$name] =  'app/rops_reports/'.$name.'.pdf';

            //send mail
            $users_in_stations_details =  UsersInStations_Details::where('stationid', $data['station_id'])->where('hasaccess', 1)->where('notification_module', 'Retail Price Comparison Report')->where('NotificationAllowed', 1)->distinct()->get(['firstname','companyname', 'station_name', 'email']);
              
            $grouped_users= array();
            $grouped_users= $this->sortbyUserInStation($users_in_stations_details);
              
            foreach ($grouped_users as $value) {
                 $mail_data = [
                   'receiver_data'=> $value,
                    'date' => $date
                  ]; 

               if(isset($value[0]['email'])){
                    Mail::to([$value[0]['email'], "support@e360africa.com"])->send(new ROPSReportMail($mail_data, $value));
                }
             // Mail::to("omoniyi.o@e360africa.com")->send(new ROPSReportMail($mail_data, $value ));
              }
    }

    private function sortbyUserInStation($users_in_stations_details){
      $grouped_users= array();
      $grouped_filenames= array();
      foreach ($users_in_stations_details as $value) {
        if($value['email'] !== null){
          if(!isset($grouped_users[$value['email']])){
            if(isset(SendROPSMail::$final_pdfs[$value['station_name']])){
            $grouped_users[$value['email']] = array();
            $grouped_filenames[$value['email']] = array();
              array_push($grouped_users[$value['email']], ['email' => $value['email'], 'firstname' => $value['firstname'], 'station' => $value['station_name'], 'companyname' => $value['companyname'], 'pdf' => SendROPSMail::$final_pdfs[$value['station_name']]]);
           }
          }else{    
            if(isset(SendROPSMail::$final_pdfs[$value['station_name']])){
              array_push($grouped_users[$value['email']], ['email' => $value['email'], 'firstname' => $value['firstname'],'station' => $value['station_name'],'companyname' => $value['companyname'], 'pdf' => SendROPSMail::$final_pdfs[$value['station_name']]]);
          }
          }
          }
    }
    return $grouped_users;
  
}
   



}
