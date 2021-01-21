<?php

namespace App\Http\Controllers\Auth;

use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use App\Models\Setting;
use App\Models\Ldap;
use App\Models\User;
use Auth;
use Config;
use Illuminate\Http\Request;
use Input;
use Redirect;
use Log;
use View;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
use ThrottlesLogins;
 protected $username = 'username';
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
         $this->middleware('guest', ['except' => ['logout','postTwoFactorAuth','getTwoFactorAuth','getTwoFactorEnroll']]);
        \Session::put('backUrl', \URL::previous());
    }

    function showLoginForm(Request $request)
    {
        $this->loginViaRemoteUser($request);
        if (Auth::check()) {
            return redirect()->intended('dashboard');
        }

        if (Setting::getSettings()->login_common_disabled == "1") {
            return view('errors.403');
        }

        return view('auth.login');
    }

        public function login(Request $request)
    {
        if (Setting::getSettings()->login_common_disabled == "1") {
            return view('errors.403');
        }

        $validator = $this->validator(Input::all());

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        // $this->maxLoginAttempts = config('auth.throttle.max_attempts');
        // $this->lockoutTime = config('auth.throttle.lockout_duration');

        // if ($lockedOut = $this->hasTooManyLoginAttempts($request)) {
        //     $this->fireLockoutEvent($request);
        //     return $this->sendLockoutResponse($request);
        // }

        $user = null;

        // // Should we even check for LDAP users?
        // if (Setting::getSettings()->ldap_enabled=='1') {
        //     LOG::debug("LDAP is enabled.");
        //     try {
        //         $user = $this->loginViaLdap($request);
        //         Auth::login($user, true);

        //     // If the user was unable to login via LDAP, log the error and let them fall through to
        //     // local authentication.
        //     } catch (\Exception $e) {
        //         LOG::error("There was an error authenticating the LDAP user: ".$e->getMessage());
        //     }
        // }

        // If the user wasn't authenticated via LDAP, skip to local auth
        if (!$user) {
            LOG::debug("Authenticating user against database.");
          // Try to log the user in
            if (!Auth::attempt(Input::only('username', 'password'), Input::get('remember-me', 0))) {

                // if (!$lockedOut) {
                //     $this->incrementLoginAttempts($request);
                // }

                LOG::debug("Local authentication failed.");
                return redirect()->back()->withInput()->with('error', trans('auth/message.account_not_found'));
            } else {

                  //$this->clearLoginAttempts($request);
            }
        }

        // if ($user = Auth::user()) {
        //     $user->last_login = \Carbon::now();
        //     \Log::debug('Last login:'.$user->last_login);
        //     $user->save();
        // }
        // Redirect to the users page
        return redirect()->intended()->with('success', trans('auth/message.signin.success'));
    }

    private function loginViaRemoteUser(Request $request)
    {
        $remote_user = $request->server('REMOTE_USER');
        if (Setting::getSettings()->login_remote_user_enabled == "1" && isset($remote_user) && !empty($remote_user)) {
            LOG::debug("Authenticatiing via REMOTE_USER.");
            try {
                $user = User::where('username', '=', $remote_user)->whereNull('deleted_at')->first();
                LOG::debug("Remote user auth lookup complete");
                if(!is_null($user)) Auth::login($user, true);
            } catch(Exception $e) {
                LOG::error("There was an error authenticating the Remote user: " . $e->getMessage());
            }
        }
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required',
            'password' => 'required',
        ]);
    }

    public function logout(Request $request)
    {

        $request->session()->forget('2fa_authed');

        Auth::logout();
        return redirect()->route('login')->with('success', 'You have successfully logged out!');
    }


}
