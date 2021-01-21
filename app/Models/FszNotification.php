<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FszNotification extends Model
{

   use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'fsz_notifications';

   protected $fillable = ['importfile_name','notification_id', 'created_time','party_account','currency','entries','entries_sum','credit_entries','credit_entries_sum','debit_entries','debit_entries_sum'];

   public function FszNotificationEntries()
   {
       return $this->hasMany('App\Models\FszNotificationEntry','fsz_notification_id','id');
   }
}
