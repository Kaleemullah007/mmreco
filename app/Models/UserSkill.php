<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class UserSkill extends Model
{

   // use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'user_skill';

    public function username()
    {
        return $this->belongsTo('\App\Models\User', 'user_id');
    }

    public function skill()
    {
        return $this->belongsTo('\App\Models\Skill', 'skill_id');
    }

    // For Datatable Search & Filter
    public function scopeTextSearch($query, $search,$type)
    {
        if($type == "filter")
        {
            $filterArray = json_decode($search,true);
            return $query->where(function ($query) use ($filterArray) 
            {
                if(isset($filterArray['resource_name']) && !empty($filterArray['resource_name']))
                {
                     $query->whereHas('username', function ($query) use ($filterArray) {
                            $query->where(DB::raw("LOWER(users.full_name)"), 'LIKE', '%'.strtolower($filterArray['resource_name']).'%');
                        });
                }

                if(isset($filterArray['skillname']) && !empty($filterArray['skillname']))
                {
                    $query->whereHas('skill', function ($query) use ($filterArray) {
                            $query->where('skill.skill_name', 'LIKE', '%'.$filterArray['skillname'].'%');
                        });
                }
            });
        }
        else
        {
            $search = explode('+', $search);

            return $query->where(function ($query) use ($search) {

                foreach ($search as $search) 
                {
                    $query->orWhere(function ($query) use ($search) {
                        $query->whereHas('username', function ($query) use ($search) {
                        $query->where(DB::raw("LOWER(users.full_name)"), 'LIKE', '%'.strtolower($search).'%');
                        });
                    })
                    ->orWhere(function ($query) use ($search) {
                        $query->whereHas('skill', function ($query) use ($search) {
                        $query->where('skill.skill_name', 'LIKE', '%'.$search.'%');
                        });
                    });
                }
            });
        }
    }

    public function scopeOrderUserName($query, $order)
    {
        return $query->orderBy('users.first_name', $order);
    } 
    public function scopeOrderSkill($query, $order)
    {
        return $query->orderBy('skill.skill_name', $order);
    } 
    public function scopeOrderEmpCode($query, $order)
    {
        return $query->orderBy('users.employee_num', $order);
    } 
}
