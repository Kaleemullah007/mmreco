<?php
namespace App\Models;

use App\Models\ParexModel;
use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;

class Skill extends ParexModel
{

   // use SoftDeletes;

    protected $dates = [''];
    protected $table = 'skill';

    protected $rules = array(
        'skill_name'   => 'required|min:2|max:255|unique:skill,skill_name',
    );

    protected $injectUniqueIdentifier = true;
    use ValidatingTrait;

 	protected $fillable = ['skill_name', 'status'];


 	public function scopeTextSearch($query, $search)
    {

        return $query->where(function ($query) use ($search) {
        
            $query->where('skill_name', 'LIKE', '%'.$search.'%');
        });
    }
}
