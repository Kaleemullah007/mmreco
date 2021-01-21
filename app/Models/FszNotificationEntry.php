<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FszNotificationEntry extends Model
{

   use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'fsz_notification_enteries';

   protected $fillable = ['fsz_notification_id','importfile_name' ,'amount','credit_debit_indicator','status','booking_date','booking_transaction_code','EndToEndId','TxId','Dbtr_Nm','Dbtr_AdrLine','Dbtr_Ctry','DbtrAcct_Id','Cdtr_Nm','Cdtr_AdrLine','Cdtr_Ctry','CdtrAcct_Id','Prtry_Tp','AddtlRmtInf','TxDtTm','AddtlTxInf','AddtlNtryInf','AddtlRmtInf_ref'];
}
