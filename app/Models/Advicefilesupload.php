<?php
namespace App\Models;

use App\Models\ParexModel;
use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Advicefilesupload extends ParexModel
{

   //use SoftDeletes;

    protected $dates = [''];
    protected $table = 'advicefiles_upload';

    protected $rules = array(
       
    );

    protected $injectUniqueIdentifier = true;
    use ValidatingTrait;


}
