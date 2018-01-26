<?php
/**
 * Created by PhpStorm.
 * User: e360
 * Date: 1/10/18
 * Time: 11:54 AM
 */
namespace Core\Models;
use Core\AuditTrail\AuditTable;
use Illuminate\Database\Eloquent\Model as BaseModel;
abstract class Model extends BaseModel
{
  use AuditTable;
}