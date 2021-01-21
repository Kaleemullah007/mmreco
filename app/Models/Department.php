<?php
namespace App\Models;

use App\Models\ParexModel;
use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;

class Department extends ParexModel
{

   // use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'department';

    protected $rules = array(
        'dept_name'   => 'required|min:2|max:255|unique:department,dept_name',
    );

    protected $injectUniqueIdentifier = true;
    use ValidatingTrait;

    protected $fillable = ['dept_name'];

    public function scopeTextSearch($query, $search)
    {

        return $query->where(function ($query) use ($search) {
        
            $query->where('dept_name', 'LIKE', '%'.$search.'%');
        });
    }
}
