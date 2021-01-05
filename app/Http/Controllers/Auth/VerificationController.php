<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\VerifiesEmails;
use App\Backend;
use Exception;
use View;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
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
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
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
}
