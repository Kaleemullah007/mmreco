<?php
namespace App\Models;

use App\Models\ParexModel;
use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;
use Input;
use DB;
use Carbon\Carbon;

class Dailybalcardfinancialint extends ParexModel
{

   // use SoftDeletes;

    protected $dates = [''];
    protected $table = 'daily_bal_card_financial_int';

    protected $rules = array(
       
    );

    protected $injectUniqueIdentifier = true;
    use ValidatingTrait;

    

}
