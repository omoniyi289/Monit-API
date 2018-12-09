<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/26/18
 * Time: 1:47 AM
 */

namespace Core\AuditTrail\Entity;

use Core\Models\Model;

class AuditTrails extends Model{
    protected $fillable = [
        'model', 'user_id', 'action', 'comment', 'subject', 'subject_id',
    ];
}