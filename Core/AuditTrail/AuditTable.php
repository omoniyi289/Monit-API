<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/26/18
 * Time: 1:55 AM
 */

namespace Core\AuditTrail;

use Core\AuditTrail\Entity\AuditTrails;

trait AuditTable
{
    public function logger($action, $user_id = null,$comment = null, $subject= null, $subject_id = null){
        $data = [
            'user_id' => $user_id,
            'model' => get_class($this),
            'action' => $action,
            'subject' => $subject,
            'subject_id' => $subject_id,
            'comment' => $comment,
        ];
        AuditTrails::create($data);
    }

    public function get_logs(){
        return AuditTrails::where('model', get_class($this));
    }

    public function delete_logs(){
        return AuditTrails::delete('model')->where('model', get_class($this));
    }
}