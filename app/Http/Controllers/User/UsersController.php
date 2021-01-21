<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssetFileRequest;
use App\Helpers\Helper;
use App\Models\Actionlog;
use App\Models\Group;
use App\Models\Location;
use App\Models\Setting;
use App\Http\Requests\SaveUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\User;
use App\Models\Ldap;
use Auth;
use Config;
use Crypt;
use DB;
use HTML;
use Illuminate\Support\Facades\Log;
use Input;
use Lang;
use League\Csv\Reader;
use Mail;
use Redirect;
use Response;
use Str;
use Symfony\Component\HttpFoundation\JsonResponse;
use URL;
use View;
use Illuminate\Http\Request;
use Gate;
use App\Models\Skill;
use App\Models\UserSkill;
use App\Models\Department;
use App\Models\UploadDocument;
use File;

/**
 * This controller handles all actions related to Users for
 * the Parextech Asset Management application.
 *
 * @version    v1.0
 */


class UsersController extends Controller
{


    /**
    * Returns a view that invokes the ajax tables which actually contains
    * the content for the users listing, which is generated in getDatatable().
    *
    * @author [A. Gianotto] [<snipe@snipe.net>]
    * @see UsersController::getDatatable() method that generates the JSON response
    * @since [v1.0]
    * @return View
    */
    public function getIndex()
    {
    	$filterColumn = array();
       
        $filterColumn[]=array("filter" =>array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));

        // $filterColumn[]=array("filter" => array("type" => "input"));
       // $filterColumn[]=array("filter" => array("type" => "input"));
       // $filterColumn[]=array("filter" => array("type" => "input"));
       // $filterColumn[]=array("filter" => array("type" => "input"));
        //$filterColumn[]=array("filter" => array("type" => "input"));
        //$filterColumn[]=array("filter" => array("type" => "input"));

        return View::make('users/index')->with('filterColumn', $filterColumn);

    }

    /**
    * Returns a view that displays the user creation form.
    *
    * @author [A. Gianotto] [<snipe@snipe.net>]
    * @since [v1.0]
    * @return View
    */
    public function getCreate()
    {
        return View::make('users/edit')
        ->with('user', new User)
        ->with('userRole',array("user"=>"User","management" => "Management" , "head_program" => "Head-Program" , "head_service" => "Head-Service" , "pm_program" => "PM-Program" ,"pm_service" => "PM-Service" , "cost" => "Cost" , "sales" => "Sales" , "scm" => "SCM" , "hod_pm" => "HOD-PM" , "TTL" => "TTL"));
    }

    /**
    * Validate and store the new user data, or return an error.
    *
    * @author [A. Gianotto] [<snipe@snipe.net>]
    * @since [v1.0]
    * @return Redirect
    */
    public function postCreate(SaveUserRequest $request)
    {
       
        $user = new User;
        
        $user->email = $data['email'] = e($request->input('email'));
       $user->username = $data['username'] = e($request->input('username'));
        if ($request->has('password')) {
           $user->password = bcrypt($request->input('password'));
           $data['password'] =  $request->input('password');
       }
       
        $user->first_name = e($request->input('first_name'));
        $user->last_name = e($request->input('last_name'));
        $user->full_name = e($request->input('first_name'))." ".e($request->input('last_name'));
        $user->locale = 'en';
      
        $user->activated = 1;
       
        $user->phone = e($request->input('phone'));
      
        $user->pin_code = e($request->input('pin_code'));
        

         
        $user->address = e($request->input('address'));   
        $user->address2 = e($request->input('address2'));   
        $user->city = e($request->input('city'));   
        $user->login_enable = 1;

        $user->permissions =  json_encode(array("admin"=>"1"));


        if ($user->save()) 
        {

            
			$actionlog=array();
			$actionlog=array(
						  'item_id'=>$user->id,
						  'item_type'=>User::class,
						  'target_id'=>$user->id,
						  'target_type'=>User::class,
						  'new_data'=>$request->all(),
						  'note'=>'new user created',
						  'logaction'=>'create user',
						  
						  );
			$result_log= $this->Actionlog($actionlog);

            if (($request->input('email_user') == 1) && ($request->has('email'))) {
              // Send the credentials through email
                $data = array();
                $data['email'] = e($request->input('email'));
                $data['username'] = e($request->input('username'));
                $data['first_name'] = e($request->input('first_name'));
                $data['password'] = e($request->input('password'));

                Mail::send('emails.send-login', $data, function ($m) use ($user) {
                    $m->to($user->email, $user->first_name . ' ' . $user->last_name);
                    //$m->replyTo(config('mail.reply_to.address'), config('mail.reply_to.name'));
                    $m->subject(trans('mail.welcome', ['name' => $user->first_name]));
                });
            }
            return redirect::route('users')->with('success', trans('admin/users/message.success.create'));
        }

        return redirect()->back()->withInput()->withErrors($user->getErrors());



    }

    /**
    * JSON handler for creating a user through a modal popup
    *
    * @todo Handle validation more graciously
    * @author [B. Wetherington] [<uberbrady@gmail.com>]
    * @since [v1.8]
    * @return string JSON
    */
    public function store()
    {

        $user = new User;
        $inputs = Input::except('csrf_token', 'password_confirm', 'groups', 'email_user');
        $inputs['activated'] = true;

        $user->first_name = e(Input::get('first_name'));
        $user->last_name = e(Input::get('last_name'));
        $user->full_name = e(Input::get('first_name'))." ".e(Input::get('last_name'));
        $user->username = e(Input::get('username'));
        $user->email = e(Input::get('email'));
        if (Input::has('password')) {
            $user->password = bcrypt(Input::get('password'));
        }

        $user->activated = true;



      // Was the user created?
        if ($user->save()) {

            if (Input::get('email_user') == 1) {
                // Send the credentials through email
                $data = array();
                $data['email'] = e(Input::get('email'));
                $data['first_name'] = e(Input::get('first_name'));
                $data['last_name'] = e(Input::get('last_name'));
                $data['password'] = e(Input::get('password'));

                Mail::send('emails.send-login', $data, function ($m) use ($user) {
                    $m->to($user->email, $user->first_name . ' ' . $user->last_name);
                    $m->replyTo(config('mail.reply_to.address'), config('mail.reply_to.name'));
                    $m->subject(trans('mail.welcome', ['name' => $user->first_name]));
                });
            }

            return JsonResponse::create($user);

        } else {
            return JsonResponse::create(["error" => "Failed validation: " . print_r($user->getErrors(), true)], 500);
        }



    }

    /**
    * Returns a view that displays the edit user form
    *
    * @author [A. Gianotto] [<snipe@snipe.net>]
    * @since [v1.0]
    * @param int $id
    * @return View
    */

    private function filterDisplayable($permissions) {
        $output = null;
        foreach($permissions as $key=>$permission) {
                $output[$key] = array_filter($permission, function($p) {
                    return $p['display'] === true;
                });
            }
        return $output;
    }

    public function getEdit($id = null)
    {
        try 
        {
           
            $user = User::find($id);
        } 
        catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = trans('admin/users/message.user_not_found', compact('id'));

            // Redirect to the user management page
            return redirect()->route('users')->with('error', $error);
        }

        // Show the page
        return View::make('users/edit', compact( 'user'))
        				->with('status', array("Active"=>"Active","In Active"=>"In Active"))
                        ->with('userRole',array("user"=>"User","management" => "Management" , "head_program" => "Head-Program" , "head_service" => "Head-Service" , "pm_program" => "PM-Program" ,"pm_service" => "PM-Service" , "cost" => "Cost" , "sales" => "Sales" , "scm" => "SCM" , "hod_pm" => "HOD-PM", "TTL" => "TTL"));
    }

    /**
    * Validate and save edited user data from edit form.
    *
    * @author [A. Gianotto] [<snipe@snipe.net>]
    * @since [v1.0]
    * @param  int  $id
    * @return Redirect
    */
    public function postEdit(UpdateUserRequest $request, $id = null)
    {
    	//print_r(e($request->input('status')));exit();
        //$permissions = $request->input('permissions', array());
       // app('request')->request->set('permissions', $permissions);
       
        if (config('app.lock_passwords')) {
            return redirect()->route('users')->with('error', 'Denied! You cannot update user information on the demo.');
        }
        //try 
        //{
            $user = User::find($id);
            
        // Do we want to update the user password?
       if ($request->has('password')) {
           $user->password = bcrypt($request->input('password'));
       }
        if ( $request->has('username')) {
           $user->username = e($request->input('username'));
        }
        $user->email = e($request->input('email'));
        $user->status = e($request->input('status'));


       // Update the user
        $user->first_name = e($request->input('first_name'));
        $user->last_name = e($request->input('last_name'));
        $user->full_name = e($request->input('first_name'))." ".e($request->input('last_name'));
    
        $user->activated = 1;
       
        $user->phone = e($request->input('phone'));
       
        $user->pin_code = e($request->input('pin_code'));
        
        $user->address = e($request->input('address'));   
        $user->address2 = e($request->input('address2'));   
        $user->city = e($request->input('city'));   

        if ($user->manager_id == "") {
            $user->manager_id = null;
        }

        if ($user->location_id == "") {
            $user->location_id = null;
        }

        if ($user->company_id == "") {
            $user->company_id = 1;
        }

        if ($user->save()) 
        {      	
			$actionlog=array();
			$actionlog=array(
						  'item_id'=>$user->id,
						  'item_type'=>User::class,
						  'target_id'=>$user->id,
						  'target_type'=>User::class,
						  'new_data'=>$request->all(),
						  'note'=>'user edited',
						  'logaction'=>'edit user',
						  
						  );
			$result_log= $this->Actionlog($actionlog);
			


            // Prepare the success message
            $success = trans('admin/users/message.success.update');

            // Redirect to the user page
            return redirect()->route('users')->with('success', $success);
        }

            return redirect()->back()->withInput()->withErrors($user->getErrors());

    }

    /**
    * Delete a user
    *
    * @author [A. Gianotto] [<snipe@snipe.net>]
    * @since [v1.0]
    * @param  int  $id
    * @return Redirect
    */
    public function getDelete($id = null)
    {
        try {
            // Get user information
            $user = User::find($id);

            // Check if we are not trying to delete ourselves
            if ($user->id === Auth::user()->id) {
                // Redirect to the user management page
                return redirect()->route('users')->with('error', trans('admin/users/message.error.delete'));
            }

            // Do we have permission to delete this user?
            if ((Gate::denies('users.delete') || (config('app.lock_passwords')))) {
                return redirect()->route('users')->with('error', 'Insufficient permissions!');
            }

			$actionlog=array();
			$actionlog=array(
						  'item_id'=>$user->id,
						  'item_type'=>User::class,
						  'target_id'=>$user->id,
						  'target_type'=>User::class,
						  'new_data'=>$user,
						  'note'=>'user deleted',
						  'logaction'=>'delete user',
						  
						  );
			$result_log= $this->Actionlog($actionlog);
			

            $user->delete();



            $success = trans('admin/users/message.success.delete');
            return redirect()->route('users')->with('success', $success);

        } catch (UserNotFoundException $e) {
            return redirect()->route('users')->with('error', trans('admin/users/message.user_not_found', compact('id')));
        }
    }


    /**
    * Restore a deleted user
    *
    * @author [A. Gianotto] [<snipe@snipe.net>]
    * @since [v1.0]
    * @param  int  $id
    * @return Redirect
    */
    public function getRestore($id = null)
    {

            // Get user information
        if (!$user = User::onlyTrashed()->find($id)) {
            return redirect()->route('users')->with('error', trans('admin/users/messages.user_not_found'));
        }

        if (!Company::isCurrentUserHasAccess($user)) {
            return redirect()->route('users')->with('error', trans('general.insufficient_permissions'));
        } else {

            // Restore the user
            if (User::withTrashed()->where('id', $id)->restore()) {
                return redirect()->route('users')->with('success', trans('admin/users/message.success.restored'));
            } else {
                return redirect()->route('users')->with('error', 'User could not be restored.');
            }

        }
    }


    /**
    * Return a view with user detail
    *
    * @author [A. Gianotto] [<snipe@snipe.net>]
    * @since [v1.0]
    * @param  int  $userId
    * @return View
    */
    public function getView($userId = null)
    {
        $user = User::
        		with( 'userdept')->find($userId);
        // return $user;
        $user_skill = UserSkill::select('skill.skill_name')
                    ->leftjoin('skill', 'user_skill.skill_id', '=', 'skill.id')
                    //->leftjoin('domain', 'user_skill.domain_id', '=', 'domain.id')
                    ->where('user_skill.user_id', $userId)->get();
        // return $user_skill;
        $userlog = array();
        //$userlog = $user->userlog->load('item');

        if (isset($user->id)) 
        {
           
                return View::make('users/view', compact('user', 'userlog', 'user_skill'));
          
        } 
        else 
        {
            // Prepare the error message
            $error = trans('admin/users/message.user_not_found', compact('id'));

            // Redirect to the user management page
            return redirect()->route('users')->with('error', $error);
        }
    }

    /**
    * Unsuspend a user.
    *
    * @author [A. Gianotto] [<snipe@snipe.net>]
    * @since [v1.0]
    * @param  int  $id
    * @return Redirect
    */
    public function getUnsuspend($id = null)
    {
        try {
            // Get user information
            //$user = User::find($id);

            // Check if we are not trying to unsuspend ourselves
            if ($user->id === Auth::user()->id) {
                // Prepare the error message
                $error = trans('admin/users/message.error.unsuspend');

                // Redirect to the user management page
                return redirect()->route('users')->with('error', $error);
            }

            // Do we have permission to unsuspend this user?
            if ($user->isSuperUser() && !Auth::user()->isSuperUser()) {
                // Redirect to the user management page
                return redirect()->route('users')->with('error', 'Insufficient permissions!');
            }

            // Prepare the success message
            $success = trans('admin/users/message.success.unsuspend');

            // Redirect to the user management page
            return redirect()->route('users')->with('success', $success);
        } catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = trans('admin/users/message.user_not_found', compact('id'));

            // Redirect to the user management page
            return redirect()->route('users')->with('error', $error);
        }
    }

    /**
    * Return user import view
    *
    * @author [A. Gianotto] [<snipe@snipe.net>]
    * @since [v1.0]
    * @return View
    */
    public function getImport()
    {
        // Get all the available groups
        //$groups = Sentry::getGroupProvider()->findAll();
        // Selected groups
        $selectedGroups = Input::old('groups', array());
        // Get all the available permissions
        $permissions = config('permissions');
        //$this->encodeAllPermissions($permissions);
        // Selected permissions
        $selectedPermissions = Input::old('permissions', array('superuser' => -1));
        //$this->encodePermissions($selectedPermissions);
        // Show the page
        return View::make('users/import', compact('groups', 'selectedGroups', 'permissions', 'selectedPermissions'));
    }

    /**
    * Handle user import file
    *
    * @author [A. Gianotto] [<snipe@snipe.net>]
    * @since [v1.0]
    * @return Redirect
    */
    public function postImport()
    {

        if (!ini_get("auto_detect_line_endings")) {
            ini_set("auto_detect_line_endings", '1');
        }

        $csv = Reader::createFromPath(Input::file('user_import_csv'));
        $csv->setNewline("\r\n");

        if (Input::get('has_headers') == 1) {
            $csv->setOffset(1);
        }
        
        $duplicates = '';
        ///$path = config('app.private_uploads').'/users';
        $csvfilename="users-".date('Y-m-d-his').".csv";
        //$csvfile = fopen($path."/".$csvfilename, 'w');

        $path1 = storage_path();
        $path1  = str_replace('storage', '', $path1);
        $path = $path1.'public/uploads/users';
        $csvfile = fopen($path."/".$csvfilename, 'w');

        $rcount=0;
        $nbInsert = $csv->each(function ($row) use ($duplicates,&$rcount,&$csvfile) 
        {
            $objUser = new User();
            $objSkill = new Skill;
            if (array_key_exists(2, $row))
            {                
                $rcount++;

                if (Input::get('activate') == 1) {
                    $activated = '1';
                } else {
                    $activated = '0';
                }
                if($rcount==1)
                {

                    $headers=[
                    // strtolower to prevent Excel from trying to open it as a SYLK file
                   // strtolower(trans('general.id')),
                    trans('admin/users/table.first_name'),
                    trans('admin/users/table.last_name'),
                    trans('admin/users/table.username'),
                    trans('admin/users/table.email'),
                    trans('admin/users/table.address'),
                    trans('admin/users/table.address2'),
                    trans('admin/users/table.city'),
                    trans('admin/users/table.zip_code'),
                    trans('admin/users/table.contact_number'),
                    trans('admin/users/table.trade'),
                    trans('admin/users/table.user_role'),
                    trans('admin/users/table.user_skill'),
                    trans('admin/users/table.dob'),
                    trans('admin/users/table.nwm_reference'),
                    trans('admin/users/table.ni_reference'),
                    trans('admin/users/table.company'),
                    trans('admin/users/table.uplifts_t1'),
                    trans('admin/users/table.uplifts_t2'),
                    trans('admin/users/table.performance_score'),
                    trans('admin/users/table.warnings'),
                    trans('admin/users/table.blacklist'),
                    'Status',
                    ];
                
                    fputcsv($csvfile, $headers);
                
                }
                $pass = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 15);


                // Department
                $user_dept_id = '';
                if (array_key_exists('6', $row)) {

                    if (trim($row[6]) != '') {
                          $user_dept_id= $objUser->checkDepartment($row[6]);
                    }
                    else
                    {
                        $user_dept_id = '';
                    }
                }

                //Manager
                $user_manager_id = '';
                if (array_key_exists('9', $row)) {

                    if (trim($row[9]) != '') {
                          $user_manager_id= $objUser->checkManager($row[9]);
                    }
                    else
                    {
                        $user_manager_id = '';
                    }
                }

              

                //Skill
                $user_skill_id = array();
                if (array_key_exists('11', $row)) {

                    if (trim($row[11]) != '') 
                    {
                        $us = explode(',', trim($row[11]));
                        for ($i=0; $i < count($us); $i++) 
                        { 
                            $uSkillId = $objUser->checkSkill($us[$i], $user_domain_id);

                            if(!empty($uSkillId))
                            $user_skill_id[] = $uSkillId;
                        }                          
                    }
                    else
                    {
                        $user_skill_id = [];
                    }
                }


                try 
                {
                    for($i=0;$i<=23;$i++)
                      {
                        if(!isset($row[$i]))
                        {
                          $row[$i] = "";
                        }
                      }

                    $values = [
                       // $client->id,
                        trim(e($row[0])),
                        trim(e($row[1])),
                        trim(e($row[2])),
                        trim(e($row[3])),
                        trim(e($row[4])),
                        trim(e($row[5])),
                        trim(e($row[6])),
                        trim(e($row[7])),
                        trim(e($row[8])),
                        trim(e($row[9])),
                        trim(e($row[10])),
                        trim(e($row[11])),
                        trim(e($row[12])),
                        trim(e($row[13])),
                        trim(e($row[14])),
                        trim(e($row[15])),
                        trim(e($row[16])),
                        trim(e($row[17])),
                        trim(e($row[18])),
                        trim(e($row[19])),
                        trim(e($row[20])),
                        trim(e($row[21])),
                        // trim(e($row[22])),
                        // trim(e($row[23])),
                        // trim(e($row[24])),
                       ];

                       
                    // Check if this email already exists in the system
                    $user = User::where('username', $row[2])->first();
                    if ($user) 
                    {
                        
                        $userRole = '';
                        if(!empty(trim(e($row[10]))))
                        {
                            $userRole = trim(e($row[10]));
                        }
                        else
                        {
                            $userRole = "user";
                        }
                  
                        $newuser = array(
                            'first_name' => trim(e($row[0])),
                            'last_name' => trim(e($row[1])),
                            'full_name' => trim(e($row[0]))." ".trim(e($row[1])),
                            'username' => trim(e($row[2])),
                            'email' => trim(e($row[3])),
                            'address' => trim(e($row[4])),
                            'address2' => trim(e($row[5])),
                            'city' => trim(e($row[6])),
                            'pin_code' => trim(e($row[7])),
                            'phone' => trim(e($row[8])),
                            'jobtitle' => trim(e($row[9])),
                            'user_role' => $userRole,
                          
                            'notes' => 'Imported user',
                            'dob' => date('Y-m-d',strtotime(trim(e($row[12])))),
                            'nwm_reference' => trim(e($row[12])),
                            'ni_reference' => trim(e($row[14])),
                            'company' => trim(e($row[15])),
                            'uplifts_t1' => trim(e($row[16])),
                            'uplifts_t2' => trim(e($row[17])),
                            'performance_score' => trim(e($row[18])),
                            'warnings' => trim(e($row[19])),
                            'blacklist' => trim(e($row[20])),
                            
                           
                        );
                        User::where('id', $user->id)->update($newuser);

                        if(!empty($user_skill_id))
                        {
                            UserSkill::where('user_id', $user->id)->delete();

                            for ($i=0; $i < count($user_skill_id); $i++) 
                            { 
                                $uss = new UserSkill;

                                $uss->user_id = $user->id;
                                //$uss->domain_id = $user_domain_id;
                                $uss->skill_id = $user_skill_id[$i];

                                 $uss->save();
                            }
                        }
                        


                        $values[]="Updated";
                    } 
                    else 
                    {
                        $userRole = '';
                        if(!empty(trim(e($row[13]))))
                        {
                            $userRole = trim(e($row[13]));
                        }
                        else
                        {
                            $userRole = "user";
                        }

                        $newuser = array(
                            'first_name' => trim(e($row[0])),
                            'last_name' => trim(e($row[1])),
                            'full_name' => trim(e($row[0]))." ".trim(e($row[1])),
                            'username' => trim(e($row[2])),
                            'email' => trim(e($row[3])),
                            'address' => trim(e($row[4])),
                            'address2' => trim(e($row[5])),
                            'city' => trim(e($row[6])),
                            'pin_code' => trim(e($row[7])),
                            'phone' => trim(e($row[8])),
                            'jobtitle' => trim(e($row[9])),
                            'user_role' => $userRole,
                          
                            'notes' => 'Imported user',
                            'dob' => date('Y-m-d',strtotime(trim(e($row[12])))),
                            'nwm_reference' => trim(e($row[12])),
                            'ni_reference' => trim(e($row[14])),
                            'company' => trim(e($row[15])),
                            'uplifts_t1' => trim(e($row[16])),
                            'uplifts_t2' => trim(e($row[17])),
                            'performance_score' => trim(e($row[18])),
                            'warnings' => trim(e($row[19])),
                            'blacklist' => trim(e($row[20])),
                        );

                        $user=DB::table('users')->insertGetId($newuser);
                        $uid = $user;
                        if(!empty($user_skill_id))
                        {
                            for ($i=0; $i < count($user_skill_id); $i++) 
                            { 
                                $uss = new UserSkill;

                                $uss->user_id = $uid;
                                //$uss->domain_id = $user_domain_id;
                                $uss->skill_id = $user_skill_id[$i];

                                // $uss->save();
                            }
                        }
                        if($user)
                        {
                            $values[]="Success";
                        }
                        else
                        {
                            $values[]="Failure";
                        }
                        
                        if (((Input::get('email_user') == 1) && !config('app.lock_passwords')))

                        {
                            // Send the credentials through email
                            if ($row[3] != '') {
                                $data = array();
                                $data['username'] = trim(e($row[2]));
                                $data['first_name'] = trim(e($row[0]));
                                $data['password'] = $pass;

                                if ($newuser['email']) {
                                    Mail::send('emails.send-login', $data, function ($m) use ($newuser) {
                                        $m->to($newuser['email'], $newuser['first_name'] . ' ' . $newuser['last_name']);
                                      //  $m->replyTo(config('mail.reply_to.address'), config('mail.reply_to.name'));
                                        $m->subject(trans('mail.welcome', ['name' => $newuser['first_name']]));
                                    });
                                }
                            }
                        }
                    }
                    fputcsv($csvfile, $values);
                } 
                catch (Exception $e) 
                {
                    echo 'Caught exception: ', $e->getMessage(), "\n";
                }
                return true;
            }
        });
        fclose($csvfile); 

        $headers = array(
        'Content-Type' => 'text/csv',
        );

        return Response::download($path."/".$csvfilename,$csvfilename, $headers)->deleteFileAfterSend(true);

       
        //return redirect()->route('users')->with('duplicates', $duplicates)->with('success', 'Success');
    }

    /**
    * Return JSON response with a list of user details for the getIndex() view.
    *
    * @author [A. Gianotto] [<snipe@snipe.net>]
    * @since [v1.6]
    * @see UsersController::getIndex() method that consumed this JSON response
    * @return string JSON
    */
    public function getDatatable(Request $request, $status = null)
    {

    	$users = User::select('users.id','users.status','users.employee_num','users.address','users.email','users.username','users.address2','users.city','users.first_name','users.last_name','users.created_at','users.notes','users.company_id', 'users.deleted_at','users.activated','users.nwm_reference','users.ni_reference','users.company', DB::raw('GROUP_CONCAT(skill.skill_name) as skill_name'),'users.uplifts_t1','users.uplifts_t2','users.dob','users.performance_score','users.pin_code','users.phone','users.login_enable','users.warnings','users.blacklist','users.new_gross_salary')
        ->leftjoin('user_skill as us', 'users.id', '=', 'us.user_id')
		->leftjoin('skill', 'us.skill_id', '=', 'skill.id')
		//->leftjoin('domain', 'us.domain_id', '=', 'domain.id')
		->groupBy('users.id')
        ->with('manager', 'groups', 'userdept');

        if (Input::has('offset')) {
            $offset = e(Input::get('offset'));
        } else {
            $offset = 0;
        }

        if (Input::has('limit')) {
            $limit = e(Input::get('limit'));
        } else {
            $limit = 50;
        }

        if (Input::get('sort')=='name') {
            $sort = 'first_name';
        } else {
            $sort = e(Input::get('sort'));
        }
        
        // $users = Company::scopeCompanyables($users);

        switch ($status) {
            case 'deleted':
                $users = $users->withTrashed()->Deleted();
                break;
        }

        // For Datatable Search & Filter
        if (Input::has('filter')) 
        {
          $users = $users->TextSearch(Input::get('filter'),"filter");
        }
        else
        {
	        if (Input::has('search')) {
	            $users = $users->TextSearch(e(Input::get('search')),"search");
	        }
    	}

    	$allowed_columns = ['last_name','first_name','email','username','address', 'address2','city', 'nwm_reference','ni_reference','groups','activated','created_at', 'company','uplifts_t1','uplifts_t1','uplifts_t2' , 'dob' , 'phone' , 'pin_code' ,'performance_score' , 'warnings'  ,'blacklist'];

    	$order = Input::get('order') === 'asc' ? 'asc' : 'desc';
        $sort = in_array(Input::get('sort'), $allowed_columns) ? e(Input::get('sort')) : 'users.created_at';

        switch ($sort) 
        {
            case 'manager':
                $users = $users->OrderManager($order);
                break;
            case 'skill':
                $users = $users->OrderSkill($order);
                break;
          //  case 'domain':
             //   $users = $users->OrderDomain($order);
              //  break;
            default:
                $users = $users->orderBy($sort, $order);
                break;
        }

        // $userCount = $users->count();
        $userCount = $users->get();
        $userCount = count($userCount);

        if($limit != 0)
            $users = $users->skip($offset)->take($limit)->get();
        else
            $users = $users->get();

		// return $users;
        // $users = $users->get();
        $rows = array();
        // return $users;
        foreach ($users as $user) {
            $group_names = '';
            $inout = '';
            $actions = '<nobr>';

            foreach ($user->groups as $group) 
            {
                $group_names .= '<a href="' . config('app.url') . '/admin/groups/' . $group->id . '/edit" class="label  label-default">' . $group->name . '</a> ';
            }


            if (!is_null($user->deleted_at)) {
                if (Gate::allows('users.delete')) {
                    $actions .= '<a href="' . route('restore/user',
                            $user->id) . '" class="btn btn-warning btn-sm" ><i class="fa fa-share icon-white"></i></a> ';
                }
            } else {

                if (Gate::allows('users.delete')) {
                    if ($user->accountStatus() == 'suspended') {
                        $actions .= '<a href="' . route('unsuspend/user',
                                $user->id) . '" class="btn btn-default btn-sm"><span class="fa fa-clock-o"></span></a> ';
                    }
                }
                if (Gate::allows('users.edit')) {
                    $actions .= '<a href="' . route('update/user',
                            $user->id) . '" class="btn btn-warning btn-sm" data-original-title="Edit User" data-tooltip="tooltip"><i class="fa fa-pencil icon-white"></i></a> ';

                    // $actions .= '<a href="' . route('clone/user',
                    //         $user->id) . '" class="btn btn-info btn-sm"><i class="fa fa-clone"></i></a>';
                }
                if (Gate::allows('users.delete')) {
                    if ((Auth::user()->id !== $user->id) && (!config('app.lock_passwords'))) {
                        $actions .= '<a data-html="false" class="btn delete-asset btn-danger btn-sm" data-toggle="modal" href="' . route('delete/user',
                                $user->id) . '" data-content="Are you sure you wish to delete this user?" data-title="Delete ' . htmlspecialchars($user->first_name) . '?" onClick="return false;" data-original-title="Delete User" data-tooltip="tooltip"><i class="fa fa-trash icon-white"></i></a> ';
                    } else {
                        $actions .= ' <span class="btn delete-asset btn-danger btn-sm disabled"><i class="fa fa-trash icon-white"></i></span>';
                    }
                } else {
                    $actions.='';
                }
            }

            $actions .= '</nobr>';

            $us = UserSkill::where('user_skill.user_id', $user->id)
                    ->join('skill', 'user_skill.skill_id', '=', 'skill.id')
                    ->pluck('skill.skill_name')->toArray();
            $us = implode(',', $us);

          


            $rows[] = array(
                'id'         => $user->id,
                'checkbox'      => ($status!='deleted') ? '<div class="text-center hidden-xs hidden-sm"><input type="checkbox" name="edit_user['.e($user->id).']" class="one_required"></div>' : '',
                'name'          => '<a title="'.e($user->fullName()).'" href="'.config('app.url').'/admin/users/'.e($user->id).'/view">'.e($user->fullName()).'</a>',
                
                'address'          => e($user->address),
                'address2'          =>e($user->address2),
                'email'         => e($user->email),
                'username'         => e($user->username),
                'city'      => ($user->city) ? e($user->city) : '',
                'nwm_reference' => ($user->nwm_reference) ? e($user->nwm_reference) : '',
              
                'skill' => '<span style="display:block; white-space:nowrap; word-break:break-word;" data-toggle="tooltip" data-original-title="'.e($user->skill_name).'">'. e($user->skill_name) .'</span>',
            

                'ni_reference'  => e($user->ni_reference),
                'groups'        => $group_names,
                'notes'         => e($user->notes),
                'company'        => e($user->company),
                'created_at' => ($user->created_at!='')  ? e($user->created_at->format('j-M-Y H:i')) : '',
                'activated'      => ($user->activated=='1') ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times  text-danger"></i>',
                'actions'       => ($actions) ? $actions : '',
              

                
                'uplifts_t1'         => e($user->uplifts_t1),
                'uplifts_t2'         => e($user->uplifts_t2),
                'pin_code'         => e($user->pin_code),
                'phone'         => e($user->phone),
                'performance_score'         => e($user->performance_score),
                'warnings'         => e($user->warnings),
                'status'         => e($user->status),
              
                'dob'         => ($user->dob != '0000-00-00' && $user->dob != '') ? date('d-M-Y',strtotime(e($user->dob))) : '-',
           
            );
        }

        $data = array('total'=>$userCount, 'rows'=>$rows);
        return $data;
    }

    /**
    * Return JSON response with a list of user details for the getIndex() view.
    *
    * @author [A. Gianotto] [<snipe@snipe.net>]
    * @since [v1.6]
    * @param int $userId
    * @return string JSON
    */
    public function postUpload(AssetFileRequest $request, $userId = null)
    {

        $user = User::find($userId);
        $destinationPath = config('app.private_uploads') . '/users';

        if (isset($user->id)) {

            if (!Company::isCurrentUserHasAccess($user)) {
                return redirect()->route('users')->with('error', trans('general.insufficient_permissions'));
            }

            foreach (Input::file('file') as $file) {

                $extension = $file->getClientOriginalExtension();
                $filename = 'user-' . $user->id . '-' . str_random(8);
                $filename .= '-' . str_slug($file->getClientOriginalName()) . '.' . $extension;
                $upload_success = $file->move($destinationPath, $filename);

              //Log the deletion of seats to the log
                $logaction = new Actionlog();
                $logaction->item_id = $user->id;
                $logaction->item_type = User::class;
                $logaction->user_id = Auth::user()->id;
                $logaction->note = e(Input::get('notes'));
                $logaction->target_id = null;
                $logaction->new_data = $request->all();
                $logaction->created_at = date("Y-m-d H:i:s");
                $logaction->filename = $filename;
                $logaction->action_type = 'uploaded';
                $logaction->save();
				

            }
            return JsonResponse::create($logaction);

        } else {
            return JsonResponse::create(["error" => "Failed validation: ".print_r($logaction->getErrors(), true)], 500);
        }
    }


    /**
    * Delete file
    *
    * @author [A. Gianotto] [<snipe@snipe.net>]
    * @since [v1.6]
    * @param  int  $userId
    * @param  int  $fileId
    * @return Redirect
    */
    public function getDeleteFile($userId = null, $fileId = null)
    {
        $user = User::find($userId);
        $destinationPath = config('app.private_uploads').'/users';

        if (isset($user->id)) {

            if (!Company::isCurrentUserHasAccess($user)) {
                return redirect()->route('users')->with('error', trans('general.insufficient_permissions'));
            } else {
                $log = Actionlog::find($fileId);
                $full_filename = $destinationPath . '/' . $log->filename;
                if (file_exists($full_filename)) {
                    unlink($destinationPath . '/' . $log->filename);
                }
                $log->delete();
                return redirect()->back()->with('success', trans('admin/users/message.deletefile.success'));
            }
        } else {
            // Prepare the error message
            $error = trans('admin/users/message.does_not_exist', compact('id'));

            // Redirect to the licence management page
            return redirect()->route('users')->with('error', $error);
        }
    }

    /**
    * Display/download the uploaded file
    *
    * @author [A. Gianotto] [<snipe@snipe.net>]
    * @since [v1.6]
    * @param  int  $userId
    * @param  int  $fileId
    * @return mixed
    */
    public function displayFile($userId = null, $fileId = null)
    {

        $user = User::find($userId);

        // the license is valid
        if (isset($user->id)) {
            if (!Company::isCurrentUserHasAccess($user)) {
                return redirect()->route('users')->with('error', trans('general.insufficient_permissions'));
            } else {
                $log = Actionlog::find($fileId);
                $file = $log->get_src('users');
                return Response::download($file);
            }
        } else {
            // Prepare the error message
            $error = trans('admin/users/message.does_not_exist', compact('id'));

            // Redirect to the licence management page
            return redirect()->route('users')->with('error', $error);
        }
    }


    /**
    * Declare the rules for the ldap fields validation.
    *
    * @author Aladin Alaily
    * @since [v1.8]
    * @var array
    * @deprecated 3.0
    * @todo remove this method in favor of other validation
    * @var array
    */

    protected $ldapValidationRules = array(
        'firstname' => 'required|string|min:2',
        'employee_number' => 'string',
        'username' => 'required|min:2|unique:users,username',
        'email' => 'email|unique:users,email',
    );


    /**
     * Exports users to CSV
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v3.5]
     * @return \Illuminate\Http\Response
     */
    public function getExportUserCsv()
    {

        \Debugbar::disable();


        $response = new StreamedResponse(function() {
            // Open output stream
            $handle = fopen('php://output', 'w');

            User::with('assets', 'accessories', 'consumables', 'licenses', 'manager', 'groups', 'userloc', 'company','throttle')->orderBy('created_at', 'DESC')->chunk(500, function($users) use($handle) {
                $headers=[
                    // strtolower to prevent Excel from trying to open it as a SYLK file
                    strtolower(trans('general.id')),
                    trans('admin/companies/table.title'),
                    trans('admin/users/table.title'),
                    trans('admin/users/table.employee_num'),
                    trans('admin/users/table.name'),
                    trans('admin/users/table.username'),
                    trans('admin/users/table.email'),
                    trans('admin/users/table.manager'),
                    trans('admin/users/table.location'),
                    trans('general.assets'),
                    trans('general.licenses'),
                    trans('general.accessories'),
                    trans('general.consumables'),
                    trans('admin/users/table.groups'),
                    trans('general.notes'),
                    trans('admin/users/table.activated'),
                    trans('general.created_at')
                ];
                
                fputcsv($handle, $headers);

                foreach ($users as $user) {
                    $user_groups = '';

                    foreach ($user->groups as $user_group) {
                        $user_groups .= $user_group->name.', ';
                    }

                    // Add a new row with data
                    $values = [
                        $user->id,
                        ($user->company) ? $user->company->name : '',
                        $user->jobtitle,
                        $user->employee_num,
                        $user->fullName(),
                        $user->username,
                        $user->email,
                        ($user->manager) ? $user->manager->fullName() : '',
                        ($user->location) ? $user->location->name : '',
                        $user->assets->count(),
                        $user->licenses->count(),
                        $user->accessories->count(),
                        $user->consumables->count(),
                        $user_groups,
                        $user->notes,
                        ($user->activated=='1') ?  trans('general.yes') : trans('general.no'),
                        $user->created_at,

                    ];

                    fputcsv($handle, $values);
                }
            });

            // Close the output stream
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="users-'.date('Y-m-d-his').'.csv"',
        ]);

        return $response;

    }


    public function saveImgUpload(Request $request)
    {
    	// return $request->all();
    	if (!empty($_FILES)) 
    	{	
			$temporaryFile = $_FILES['file']['tmp_name'];     
			$targetFile =  "uploads/avatars/". $_FILES['file']['name'];
			// return $targetFile;
			$filename = $_FILES['file']['name'];
	        
        	$enum = substr($filename, 0, strrpos($filename, '.'));
        	
			if(move_uploaded_file($temporaryFile,$targetFile))  
			{
				User::where('employee_num', $enum)->update(['avatar' => $filename]);
				return 'success';
			}else{
				return "Error occurred while uploading the file to server!";
			}
			
		}else{
			return redirect()->back();
		}
		
		// Prepare the success message
        $success = trans('admin/users/message.success.update');

        // Redirect to the user page
        return redirect()->route('users')->with('success', $success);
    }

    public function saveDocFile(Request $request)
    {  
    	$uid = $request->get('uid');
    	$len = count($_FILES["img"]["name"]);
    	for ($i=0; $i < $len; $i++) 
		{ 
		    $tmpFilePath = $_FILES['img']['tmp_name'][$i];

		    if($tmpFilePath != "")
		    {
	        	$filePath = public_path().'/uploads/upload_document/user/';
	        	
                $shortname = $_FILES['img']['name'][$i];
                 $target_file=$filePath.$shortname;
                if(!File::exists($filePath.$uid.'/')) 
                {
					
					if (file_exists($target_file) && filesize($filePath.$shortname) == filesize($tmpFilePath)) {
					
						echo "Sorry, file already exists.";exit;
						
					}else{
                	File::makeDirectory($filePath.'/'.$uid, 0775, true, true);
	            	$filePath = $filePath.$uid.'/';
	                if(move_uploaded_file($tmpFilePath, $filePath.$shortname)) 
	                {
	                    $ud = new UploadDocument;

	                    $ud->entity_id = $uid;
	                    $ud->entity_name = 'User';
	                    $ud->file_name = $shortname;

	                    $ud->save();
	                }
	                else
	                {
	                	return redirect()->back()->with('error', 'Image not Uploaded');
	                }
					}
	            }
	            else
	            { 
			      
			        
						
	            	$filePath = $filePath.$uid.'/';
					$target_file=$filePath.$shortname;
					if (file_exists($target_file) && filesize($filePath.$shortname) == filesize($tmpFilePath)) {
					
						echo "Sorry, file already exists.";exit;
						
					}else{
	                if(move_uploaded_file($tmpFilePath, $filePath.$shortname)) 
	                {
	                    $ud = new UploadDocument;

	                    $ud->entity_id = $uid;
	                    $ud->entity_name = 'User';
	                    $ud->file_name = $shortname;
	                    
	                    $ud->save();
	                }
	                else
	                {
	                	return redirect()->back()->with('error', 'Image not Uploaded');
	                }
					}
	            }
			}
		}
		
        $success = trans('admin/users/message.success.update');

        return redirect()->back()->with('success', $success);
    }

    public function getDocListDatatable(Request $request)
    {
    	// return $request->all();

    	$uid = $request->get('userId');

    	if (Input::has('offset')) {
            $offset = e(Input::get('offset'));
        } else {
            $offset = 0;
        }

        if (Input::has('limit')) {
            $limit = e(Input::get('limit'));
        } else {
            $limit = 50;
        }

        $dlist = UploadDocument::where('entity_id', $uid)
        			->where('entity_name', 'User');
        
        $dlistCount = $dlist->count();
        $dlist = $dlist->skip($offset)->take($limit)->get();
        $rows = array();
        

        foreach ($dlist as $doc) 
        {   
        	$actions = '</nobr>';
            $actions .= '<a data-html="false" id="deleteFile'.$doc->id.'" style="margin-right: 5px;" class="btn delete-asset btn-danger btn-sm" data-toggle="modal" href="'.route('users/deleteDocFile', Helper::encryptor("encrypt",$doc->id)).'" data-content="' . trans('admin/users/message.deletefile.confirm').'" data-title="'.trans('general.delete').' '.htmlspecialchars( $doc->file_name).'?" onClick="return false;" data-original-title="Delete File" data-tooltip = "tooltip"><i class="fa fa-trash icon-white"></i></a>';

            $rows[] = array(
                'id'         => $doc->id,
                'doc_name'   => $doc->file_name,
                'actions'    => $actions,
            );
        }

        $data = array('total'=>$dlistCount, 'rows'=>$rows);
        return $data;
    }

    public function deleteDocFile($fid)
    {
    	$fid =  Helper::encryptor("decrypt",$fid);
    	$file = UploadDocument::where('id', $fid)->get();
    	$path = $file[0]['file_path'].$file[0]['file_name'];
    	// return $path;

		unlink($path);
    	$us = UploadDocument::where('id', $fid)->delete();

    	$success = trans('admin/users/message.success.update');

        return redirect()->back()->with('success', $success);
    }
}
