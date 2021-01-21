<?php
namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Watson\Validating\ValidatingTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Traits\UniqueUndeletedTrait;
use App\Models\Setting;
use App\Models\Skill;
use Illuminate\Notifications\Notifiable;
use DB;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{

    use SoftDeletes;
    use Notifiable;
    use ValidatingTrait;
    use Authenticatable;
    use CanResetPassword;
    use UniqueUndeletedTrait;

    protected $dates = ['deleted_at'];
    protected $table = 'users';
    protected $injectUniqueIdentifier = true;
    protected $fillable = ['first_name', 'last_name', 'email','password','username'];


    /**
     * Model validation rules
     *
     * @var array
     */

    protected $rules = [
        'first_name'              => 'required|string|min:1',
        'last_name'              => 'string|min:1',
        //'salary'              => 'required',
        //'gross_salary'              => 'required',
        //'user_role'              => 'required',
       // 'username'                => 'required|string|min:2|unique_undeleted',
        'email'                   => 'email',
       // 'password'                => 'required|min:6',
        // 'user_skill' => 'required',
        // 'user_domain' => 'required',
        // 'user_dept' => 'required',
    ];


    public function hasAccess($section)
    {

        if ($this->isSuperUser()) {
            return true;
        }

        $user_groups = $this->groups;


        if (($this->permissions=='')  && (count($user_groups) == 0)) {
            return false;
        }

        $user_permissions = json_decode($this->permissions, true);

        //If the user is explicitly granted, return true
        if (($user_permissions!='') && ((array_key_exists($section, $user_permissions)) && ($user_permissions[$section]=='1'))) {
            return true;
        }

        // If the user is explicitly denied, return false
        if (($user_permissions=='') || array_key_exists($section, $user_permissions) && ($user_permissions[$section]=='-1')) {
            return false;
        }

        // Loop through the groups to see if any of them grant this permission
        foreach ($user_groups as $user_group) {
            $group_permissions = (array) json_decode($user_group->permissions, true);
            if (((array_key_exists($section, $group_permissions)) && ($group_permissions[$section]=='1'))) {
                return true;
            }
        }

        return false;
    }

    public function isSuperUser()
    {
        if (!$user_permissions = json_decode($this->permissions, true)) {
            return false;
        }

        foreach ($this->groups as $user_group) {
            $group_permissions = json_decode($user_group->permissions, true);
            $group_array = (array)$group_permissions;
            if ((array_key_exists('superuser', $group_array)) && ($group_permissions['superuser']=='1')) {
                return true;
            }
        }

        if ((array_key_exists('superuser', $user_permissions)) && ($user_permissions['superuser']=='1')) {
            return true;
        }

        return false;
    }
    public function isActivated()
    {
        if ($this->activated == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function isLoginEnabled()
    {
        if ($this->login_enable == 1) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Returns the user full name, it simply concatenates
     * the user first and last name.
     *
     * @return string
     */
    public function fullName()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . " " . $this->last_name;
    }

    public function getCompleteNameAttribute()
    {
        return $this->last_name . ", " . $this->first_name . " (" . $this->username . ")";
    }

    /**
     * Returns the user Gravatar image url.
     *
     * @return string
     */
    public function gravatar()
    {

        if ($this->avatar) {
            return config('app.url').'/uploads/avatars/'.$this->avatar;
        }

        if ((Setting::getSettings()->load_remote=='1') && ($this->email!='')) {
            $gravatar = md5(strtolower(trim($this->email)));
            return "//gravatar.com/avatar/".$gravatar;
        }

        return false;

    }

   
    /**
     * Get action logs for this user
     */
    public function userlog()
    {

        return $this->hasMany('\App\Models\Actionlog', 'target_id')->orderBy('created_at', 'DESC')->withTrashed();
        
    }


    public function userdept()
    {
        return $this->belongsTo('\App\Models\Department', 'dept_id','id');
    }
    /**
     * Get the user's manager based on the assigned user
     **/
    public function manager()
    {
        return $this->belongsTo('\App\Models\User', 'manager_id')->withTrashed();
    }

    /**
     * Get user groups
     */
    public function groups()
    {
        return $this->belongsToMany('\App\Models\Group', 'users_groups');
    }


    public function accountStatus()
    {
        if ($this->throttle) {
            if ($this->throttle->suspended==1) {
                return 'suspended';
            } elseif ($this->throttle->banned==1) {
                return 'banned';
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


    /**
     * Get uploads for this asset
     */
    public function uploads()
    {
        return $this->hasMany('\App\Models\Actionlog', 'item_id')
            ->where('item_type', User::class)
            ->where('action_type', '=', 'uploaded')
            ->whereNotNull('filename')
            ->orderBy('created_at', 'desc');
    }

   
    // public function throttle()
    // {
    //     return $this->hasOne('\App\Models\Throttle');
    // }

    public function scopeGetDeleted($query)
    {
        return $query->withTrashed()->whereNotNull('deleted_at');
    }

    public function scopeGetNotDeleted($query)
    {
        return $query->whereNull('deleted_at');
    }

    /**
     * Override the SentryUser getPersistCode method for
     * multiple logins at one time
     **/
    public function getPersistCode()
    {

        if (!config('session.multi_login') || (!$this->persist_code)) {
            $this->persist_code = $this->getRandomString();

            // Our code got hashed
            $persistCode = $this->persist_code;
            $this->save();
            return $persistCode;
        }
        return $this->persist_code;
    }

    public function scopeMatchEmailOrUsername($query, $user_username, $user_email)
    {
        return $query->where('email', '=', $user_email)
            ->orWhere('username', '=', $user_username)
            ->orWhere('username', '=', $user_email);
    }

    public static function generateEmailFromFullName($name) {
        $username = User::generateFormattedNameFromFullName(Setting::getSettings()->email_format, $name);
        return $username['username'].'@'.Setting::getSettings()->email_domain;
    }

    public static function generateFormattedNameFromFullName($format = 'filastname', $users_name)
    {
        $name = explode(" ", $users_name);
        $name = str_replace("'", '', $name);
        $first_name = $name[0];
        $email_last_name = '';
        $email_prefix = $first_name;

        // If there is no last name given
        if (!array_key_exists(1, $name)) {
            $last_name='';
            $email_last_name = $last_name;
            $user_username = $first_name;

            // There is a last name given
        } else {

            $last_name = str_replace($first_name, '', $users_name);

            if ($format=='filastname') {
                $email_last_name.=str_replace(' ', '', $last_name);
                $email_prefix = $first_name[0].$email_last_name;

            } elseif ($format=='firstname.lastname') {
                $email_last_name.=str_replace(' ', '', $last_name);
                $email_prefix = $first_name.'.'.$email_last_name;

            } elseif ($format=='firstname') {
                $email_last_name.=str_replace(' ', '', $last_name);
                $email_prefix = $first_name;
            }


        }

        $user_username = $email_prefix;
        $user['first_name'] = $first_name;
        $user['last_name'] = $last_name;
        $user['username'] = strtolower($user_username);

        return $user;


    }

    public function decodePermissions()
    {
        return json_decode($this->permissions, true);
    }

    public function domain1()
    {
        return $this->belongsTo('\App\Models\Domain', 'domain_id');
    }

    public function dept()
    {
        return $this->belongsTo('\App\Models\Department', 'dept_id');
    }

    public function skill()
    {
        return $this->belongsTo('\App\Models\Skill', 'skill_id');
    }

    /**
     * Query builder scope to search on text
     *
     * @param  Illuminate\Database\Query\Builder  $query  Query builder instance
     * @param  text                              $search      Search term
     *
     * @return Illuminate\Database\Query\Builder          Modified query builder
     */
    public function scopeTextsearch($query, $search, $type)
    {
        if($type == "filter")
        {
            $filterArray = json_decode($search,true);
            return $query->where(function ($query) use ($filterArray) 
            {
                if(isset($filterArray['username']) && !empty($filterArray['username']))
                {
                    $query->where('username', 'LIKE', '%'.$filterArray['username'].'%');
                }
                if(isset($filterArray['status']) && !empty($filterArray['status']))
                {
                    $query->where('status', 'LIKE', '%'.$filterArray['status'].'%');
                }

                if(isset($filterArray['employee_num']) && !empty($filterArray['employee_num']))
                {
                    $query->where('employee_num', 'LIKE', '%'.$filterArray['employee_num'].'%');
                }


                if(isset($filterArray['name']) && !empty($filterArray['name']))
                {
                    $query->where(DB::raw("LOWER(full_name)"), 'LIKE', '%'.strtolower($filterArray['name']).'%');
                }

                
                if(isset($filterArray['email']) && !empty($filterArray['email']))
                {
                    $query->where('email', 'LIKE', '%'.$filterArray['email'].'%');
                }

                if(isset($filterArray['address']) && !empty($filterArray['address']))
                {
                    $query->where('address', 'LIKE', '%'.$filterArray['address'].'%');
                }

                if(isset($filterArray['address2']) && !empty($filterArray['address2']))
                {
                    $query->where('address2', 'LIKE', '%'.$filterArray['address2'].'%');
                }

              
                if(isset($filterArray['pin_code']) && !empty($filterArray['pin_code']))
                {
                    $query->where('pin_code', 'LIKE', '%'.$filterArray['pin_code'].'%');
                }

                if(isset($filterArray['phone']) && !empty($filterArray['phone']))
                {
                    $query->where('phone', 'LIKE', '%'.$filterArray['phone'].'%');
                }

                if(isset($filterArray['manager']) && !empty($filterArray['manager']))
                {
                    $query->whereHas('manager', function ($query) use ($filterArray) 
                    {
                        $query->where(DB::raw("LOWER(full_name)"), 'LIKE', '%'.strtolower($filterArray['manager']).'%');
                    });
                }
            });
        }
        else
        {
            $search = explode('+', $search);
            return $query->where(function ($query) use ($search) 
            {
                foreach ($search as $search) 
                {
                $query->where(DB::raw("LOWER(users.full_name)"), 'LIKE', "%".strtolower($search)."%")
                    ->orWhere('users.email', 'LIKE', "%$search%")
                    ->orWhere('users.status', 'LIKE', "%$search%")             
                    ->orWhere('users.dob', 'LIKE', "%$search%")                   
                    ->orWhere('users.pin_code', 'LIKE', "%$search%")
                    ->orWhere('users.phone', 'LIKE', "%$search%")                                       
                    ->orWhere('users.employee_num', 'LIKE', "%$search%")
                    ->orWhere('users.username', 'LIKE', "%$search%")                
                   
                    ->orWhere(function ($query) use ($search) {
                        $query->whereRaw("users.manager_id IN (select id from users where LOWER(full_name) LIKE '%".strtolower($search)."%')");
                    });
                }
            });
        }
    }


    /**
     * Query builder scope for Deleted users
     *
     * @param  Illuminate\Database\Query\Builder $query Query builder instance
     *
     * @return Illuminate\Database\Query\Builder          Modified query builder
     */

    public function scopeDeleted($query)
    {
        return $query->whereNotNull('deleted_at');
    }


    /**
     * Query builder scope to order on manager
     *
     * @param  Illuminate\Database\Query\Builder  $query  Query builder instance
     * @param  text                              $order         Order
     *
     * @return Illuminate\Database\Query\Builder          Modified query builder
     */
    public function scopeOrderManager($query, $order)
    {
        // Left join here, or it will only return results with parents
        return $query->leftJoin('users as manager', 'users.manager_id', '=', 'manager.id')->orderBy('manager.first_name', $order)->orderBy('manager.last_name', $order);
    }


    public function scopeOrderDomain($query, $order)
    {
        return $query->orderBy('domain.domain_name', $order);
    }

    public function scopeOrderSkill($query, $order)
    {
        return $query->orderBy('skill.skill_name', $order);
    }

    public function checkDepartment($deptName = null)
    {
        $dept = Department::select('department.*')
            ->Where('dept_name', 'like', '%' . $deptName . '%')->first();
      
        if (empty($dept)) {
           
            $d = new Department;
            $d->dept_name = $deptName;
            $d->save();
            return  $d->id;

        } else {
            
            return $dept->id;
        }
    }

    public function checkSkill($skillName = null, $domain_id)
    {
        $Skill = Skill::select('skill.*')->where('domain_id', $domain_id)
            ->Where('skill_name', 'like', '%' . $skillName . '%')->first();
      
        if (empty($Skill)) 
        {
            $s = new Skill;
            $s->skill_name = $skillName;
            $s->domain_id = $domain_id;
            $s->status = 'Active';
            $s->save();
            return  $s->id;

        } else {
            
            return $Skill->id;
        }
    }

    public function checkManager($employeeCode = null)
    {
        $user = User::select('users.*')
            ->Where('employee_num',"=",$employeeCode)->withTrashed()->first();

        if (empty($user)) {
            return "";
        } else {

            return $user->id;
        }
    }

    public function checkDomain($domainName = null)
    {
        $domain = Domain::select('domain.*')
            ->Where('domain_name', 'like', '%' . $domainName . '%')->first();
      
        if (empty($domain)) {
           
            $s = new Domain;
            $s->domain_name = $domainName;
            $s->save();
            return  $s->id;

        } else {
            
            return $domain->id;
        }
    }

    public static function employeeCount()
    {        
        return User::whereNull('deleted_at', 'and')
                ->count();
    }
}
