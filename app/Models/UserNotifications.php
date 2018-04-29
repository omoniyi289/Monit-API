<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\NotificationModules;
class UserNotifications extends Model
{
    protected $fillable = [
        'company_user_id', 'notification_id', 'active', 'name' ,'v1_id', 'created_at', 'module'
    ];

   
 protected $table = 'user_notifications';

  public function company_user(){
        return $this->belongsTo(User::class,"company_user_id");
    }  
    public function module(){
        return $this->belongsTo(NotificationModules::class,"notification_id");
    }  
}