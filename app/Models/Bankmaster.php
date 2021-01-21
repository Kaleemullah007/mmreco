<?php
namespace App\Models;

use App\Models\ParexModel;
use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;

class Bankmaster extends ParexModel
{

   // use SoftDeletes;

    protected $dates = [''];
    protected $table = 'bank_master';

    protected $rules = array(
        'name'   => 'required',
    );

    protected $injectUniqueIdentifier = true;
    use ValidatingTrait;

 	protected $fillable = ['name', 'status'];

}
