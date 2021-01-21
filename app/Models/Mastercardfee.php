<?php
namespace App\Models;

use App\Models\ParexModel;
use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mastercardfee extends ParexModel
{

   //use SoftDeletes;

    protected $dates = [''];
    protected $table = 'mastercardfee';

    protected $rules = array(
        'MastercardFeeId'   => 'required',
    );

    protected $injectUniqueIdentifier = true;
    use ValidatingTrait;


}
