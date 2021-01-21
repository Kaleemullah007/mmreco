<?php
namespace App\Models;

use App\Models\ParexModel;
use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bankbalancecard extends ParexModel
{

   //use SoftDeletes;

    protected $dates = [''];
    protected $table = 'bank_balance_card';

    protected $rules = array(
        'pan'   => 'required',
    );

    protected $injectUniqueIdentifier = true;
    use ValidatingTrait;

    // Get Bank Balance Card Details By Bank Balance Id.
    public static function getBankBalanceCardById($bank_balance_id){
    	return Bankbalancecard::where('bank_balance_id','=',$bank_balance_id)->where('deleted_at',null)->get()->toArray();
    }

}
