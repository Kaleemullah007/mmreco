<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FszNotificationFile extends Model
{

   use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'fsz_notifications_import_history';

   // protected $fillable = ['entity_name', 'file_name'];
}
