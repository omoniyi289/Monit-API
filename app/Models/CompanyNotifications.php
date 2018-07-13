<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyNotifications extends Model
{
    protected $fillable = [
        'notification_id','notification_name', 'company_id', 'notification_weekday','notification_daytime',
        'notification_active', 'notification_UI_slug'
    ];

   
 protected $table = 'company_notifications';
 //public function company_user_role_privileges() {
   //     return $this->hasManyThrough(Permission::class, RolePermission::class, 'permission_id', 'id', );
    //}

    
}