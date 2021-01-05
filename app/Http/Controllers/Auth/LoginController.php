<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\CsvHelper\DataHelper;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Backend;
use Exception;
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

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $backend=null;
        try{
            $backend=Backend::first();
        }catch(Exception $e){
            $backend=null;
        }
        // Fetch the Site Settings object
        $primary=$backend ? ($backend->theme ? substr($backend->theme,0,7) : '#1a6ca8') :'#1a6ca8';

        $hover=$backend ? ($backend->theme ? substr($backend->theme,0,7).'a3' : '#1a6ca8a3') :'#1a6ca8a3';
        $this->site_settings=[
            'theme'=>[
                'primary'=>$primary,
                'primary-hover'=>$hover
            ]
        ];
        View::share('site_settings', $this->site_settings);
    }

    /**
	 * The user has been authenticated.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  mixed  $user
	 * @return mixed
	 */
	protected function authenticated(Request $request, $user)
	{
		if ( User::whereEmail($request->email)->where('status', 2)->exists() ) {
    
			Auth::logout();
			session()->put('error', 'Your account is disabled. Please contact your admin.');
			return response()->json();
        }

        $dh = new DataHelper();
        $dh->tinel_su();
        
        Auth::logoutOtherDevices($request->password);
    }
}
