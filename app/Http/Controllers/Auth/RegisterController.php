<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\FileHelper;
use App\User;
use App\Mail\UserCreated;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Backend;
use Exception;
use View;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest')->except(['hasEmail','hasCitizenNumber','hasSymbolNumber']);
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
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'gender' => ['required', 'integer'],
            // 'phone' => ['required', 'integer'],
            'address' => ['required', 'string', 'max:255'],
            'school_name' => ['required', 'string', 'max:255'],
            // 'citizen_number' => ['required','unique:users'],
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $request->merge(['password' => Str::random(8)]);

        event(new Registered($user = $this->create($request->all())));

        // $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
                    ? new Response('', 201)
                    : redirect($this->redirectPath());
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $file = new FileHelper;

        if ( isset($data['photo']) && !empty($data['photo']) ) {
            $image = $file->storeFile($data['photo'], 'users');
        }
        else {
            $image = null;
        }

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'citizen_number'=>$data['citizen_number'],
            'statut'=>1,
            'role_id'=>1,
            'gender'=>2,
            'password' => Hash::make($data['password']),
            'gender' => $data['gender'],
            'address' => $data['address'],
            'phone' => $data['phone'],
            'school_name' => $data['school_name'],
            'image' => $image,
            'role_id' => 1,
            'status' => 0
        ]);

    }
    
    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['password'] = $request->password;

        Mail::to($user)->queue(new UserCreated($data));

        return redirect($this->redirectPath());
    }

    // custum apis

    public function hasEmail(Request $request)
    {
        if(isset($request->id)){

            return response()->json(['status'=>User::where('id','<>',$request->id)->whereEmail($request->email)->count()>0]);
        }

        return response()->json(['status'=>User::whereEmail($request->email)->count()>0]);
    }

    public function hasCitizenNumber(Request $request)
    {
        if(isset($request->id)){

            return response()->json(['status'=>User::where('id','<>',$request->id)->whereCitizenNumber($request->citizen_number)->count()>0]);
        }

        return response()->json(['status'=>User::whereCitizenNumber($request->citizen_number)->count()>0]);
    }

    public function hasSymbolNumber(Request $request)
    {
        if(isset($request->id)){

            return response()->json(['status'=>User::where('id','<>',$request->id)->whereSymbolNumber((int)$request->symbol_number)->count()>0]);
        }

        return response()->json(['status'=>User::whereSymbolNumber($request->symbol_number)->count()>0]);
    }
}
