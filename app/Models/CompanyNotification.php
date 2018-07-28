<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\NotificationModules;
use App\Company;

class CompanyNotification extends Model
{
    protected $fillable = [
        'notification_id','notification_name', 'company_id', 'notification_weekday','notification_daytime',
        'notification_active', 'notification_UI_slug'
    ];

   
 protected $table = 'company_notifications';
  public function notification(){
    	return $this->hasOne(NotificationModules::class, "id", 'notification_id');
    } 
    public function company(){
        return $this->belongsTo(Company::class,"company_id");
    }  

    
}