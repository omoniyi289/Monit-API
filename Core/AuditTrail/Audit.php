<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/26/18
 * Time: 2:02 AM
 */

namespace Core\AuditTrail;


use Core\AuditTrail\Entity\AuditTrails;

class Audit
{
    public static function get_audit_by_user_id($user_id){
        return AuditTrails::where('user_id',$user_id)->get();
    }

    public static function logger($action,$user_id = null,$subject, $comment = null){
        $data = array(
            'user' => $user_id,
            'action' => $action,
            'subject' => $subject,
            'comment' => $comment,
        );
        return AuditTrails::create($data);
    }
}