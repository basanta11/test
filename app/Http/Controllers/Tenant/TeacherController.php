<?php

namespace App\Http\Controllers\Tenant;

use App\Helpers\CsvHelper\CsvValidation;
use App\User;
use App\Mail\UserCreated;
use App\Helpers\FileHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\TeacherDataUpdated;
use DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Validator;

class TeacherController extends Controller
{
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FileHelper $file)
    {
        $sn=0;
        $teachers=User::with(['created_user'])->whereRoleId(3)->get()->map(function($data,$sn) use($file){
            $status=10;
            if($data->status==1)
            {
                $status=11;
            }elseif($data->status==2){
                $status=12;
            }
            $g='Male';
            if($data->gender==1){
                $g='Female';
            }
            if($data->gender==2){
                $g='Other';
            }
            return [
                'sn'=>$sn+=1,
                'id'=>$data->id,
                'name'=>$data->name,
                'email'=>$data->email,
                'address'=>$data->address,
                'phone'=>$data->phone,
                'citizen_number'=>$data->citizen_number,
                'symbol_number'=>$data->symbol_number,
                'gender'=>$g,
                'status'=>$status,
                'created_by'=>isset($data['created_user']['name'] ) ? $data['created_user']['name'] : "",
                'image'=>($data->image && $file->fileExists('users',$data->image)) ? Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/users/'.$data->image) : global_asset('assets/media/users/default.jpg'),
                'created_at'=>$data->created_at,
            ];
        });
        
        return view('admin.teachers.index',compact('teachers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.teachers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, FileHelper $file)
    {
                request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'gender' => ['required', 'integer'],
            // 'phone' => ['required', 'integer'],
            'address' => ['required', 'string', 'max:255'],
            // 'citizen_number' => ['required','unique:users'],
            'symbol_number' => ['required', 'numeric','unique:users'],
        ]);
        
        $password = Str::random(8);
        $request->merge([
            'role_id' => 3,
            'password' => bcrypt($password),
            'status' => 0
        ]);

        if ( $request->image ) {
            $image=$file->storeFile($request->image,'users');
        }
        else {
            $image = null;
        }
        
        $user = User::create(array_merge(['image'=>$image,'created_by'=>auth()->user()->id],$request->except(['image'])));
        
        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['password'] = $password;

        Mail::to($user)->queue(new UserCreated($data));
        
        return redirect('/teachers')->with('success', 'Data saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id,FileHelper $file)
    {        
        $teacher=User::findOrFail($id);
        if($teacher->role_id!=3)
            abort(403);
        $profile_image=($teacher->image && $file->fileExists('users',$teacher->image)) ? Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/users/'.$teacher->image) : global_asset('assets/media/users/default.jpg');
                
        return view('admin.teachers.show',compact('teacher','profile_image'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $teacher)
    {
        return view('admin.teachers.edit', compact('teacher'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, FileHelper $file)
    {
        $teacher=User::findOrFail($id);
        if($teacher->role_id!=3)
            abort(403);

                request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email, '.$teacher->id],
            'gender' => ['required', 'integer'],
            // 'phone' => ['required', 'integer'],
            'address' => ['required', 'string', 'max:255'],
            // 'citizen_number' => ['required','unique:users,citizen_number, '.$teacher->id],
            'symbol_number' => ['required', 'numeric','unique:users,symbol_number, '.$teacher->id],
        ]);
        
        $image=$teacher->image;
        if(isset($request->image)){
            $image=$file->updateFile($request->image,'users',$teacher->image);
        };

        $teacher->update(array_merge(['image'=>$image],$request->except(['csrf_token','_method','image'])));

        // Send email to teacher if principal/admin updates data
        $data['name'] = $teacher->name;
        $data['message'] = 'Your personal details have been updated.';

        Mail::to($teacher)->queue(new TeacherDataUpdated($data));
        
        return redirect('/teachers')->with('success', 'Data updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    // $status -> send 1 to active, 2 to deactivate
    public function statusControl($id,$status)
    {
        if(!in_array($status,[1,2])){
            return back()->with('error','Action cannot be recognized. Please try again.');
        }
        $message=$status==1 ? 'Teacher activated.': 'Teacher deactivated' ;
        $teacher=User::findOrFail($id);

        $teacher->update(['status'=>$status]);

        // Send email to teacher after status update
        $data['name'] = $teacher->name;
        $result = $status == 1 ? 'activated.' : 'deactivated.';
        $data['message'] = 'Your account has been ' . $result;

        Mail::to($teacher)->queue(new TeacherDataUpdated($data));

        return back()->with('success',$message);

    }

    // $status -> send 1 to active, 2 to deactivate
    public function statusControlBulk(Request $request,$status)
    {
        if(!in_array($status,[1,2]) || !json_decode($request->user_list)){
            return back()->with('error','Action cannot be recognized. Please try again.');
        }
        $message=$status==1 ? 'Teacher(s) activated.': 'Teacher(s) deactivated' ;
        User::whereIn('id',json_decode($request->user_list))->update(['status'=>$status]);
        
        // Send email to teachers after status update
        $updatedTeachers = User::whereIn('id',json_decode($request->user_list))->get();
        foreach ($updatedTeachers as $teacher) {
            $data['name'] = $teacher->name;
            $result = $status == 1 ? 'activated.' : 'deactivated.';
            $data['message'] = 'Your account has been ' . $result;

            Mail::to($teacher)->queue(new TeacherDataUpdated($data));
        }
            
        return back()->with('success',$message);

    }

    public function createBulk()
    {

        return view('admin.teachers.create-bulk');
    }
    public function storeBulk(Request $request)
    {
        if(!$request->data_fix)
            return back()->with('error','There was an error processing data. Please try again.');
        $dataFix=array();
        $emailData=array();
        $columns=$this->getColumnIntegrity();
        foreach(json_decode($request->data_fix) as $data){
            $g=0;
            if($data[$columns['gender']]=='female'){
                $g=1;
            }elseif($data[$columns['gender']]=='others'){
                $g=2;
            }
            
            $password = Str::random(8);
            array_push($dataFix,
                [
                    'email'=>$data[$columns['email']],
                    'name'=>$data[$columns['name']],
                    'gender'=>$g,
                    'phone'=>$data[$columns['phone']],
                    'address'=>$data[$columns['address']],
                    'citizen_number'=>isset($data[$columns['citizen_number']]) ? $this->maskCitizen($data[$columns['citizen_number']]) : null,
                    'symbol_number'=>isset($data[$columns['symbol_number']]) ? ($data[$columns['symbol_number']] ? $data[$columns['symbol_number']] : null ) : null,
                    'role_id' => 3,
                    'password' => bcrypt($password),
                    'created_by'=>auth()->user()->id,
                    'status' => 0,
                ]);
            array_push($emailData,[
                'email'=>$data[$columns['email']],
                'password'=>$password,
                'name'=>$data[$columns['name']],
            ]);
        }
      
        User::insert($dataFix);
        foreach($emailData as $user){
            Mail::to($user['email'])->queue(new UserCreated($user));
        }
        
        
        return redirect('/teachers')->with('success', 'Data upload successful.');
    }

    public function maskCitizen($mask)
    {
        if($mask){
            $mask=str_replace('-','',$mask);
            return Str::substr($mask,0,1).'-'.Str::substr($mask,1,4).'-'.Str::substr($mask,5,5).'-'.Str::substr($mask,10,2).'-'.Str::substr($mask,12,1);
        }else{
            return null;
        }
        
    }
    public function csvValidation(Request $request, CsvValidation $csv)
    {
        $user=User::get();

        $user_email=$user->pluck('email')->toArray();
        $user_citizen=$user->pluck('citizen_number')->toArray();
        $user_symbol=$user->pluck('symbol_number')->toArray();

        $all_data = array();
        $success=true;
        $columns=$this->getColumnIntegrity();
        
        $message=array();
        
        $prepare_data=$csv->prepareData(request()->file);
       
        if(!$prepare_data || count($prepare_data)<2){
            $success=false;
            array_push($message,['Insufficient Data']);
        }else{
            foreach($prepare_data as $key=>$data)
            {
                if($key){
                    // dump($data);
                    $validator = Validator::make($data, $this->getRule($columns),$this->getErr($columns));

                    if ($validator->fails()) {
                        foreach($validator->errors()->messages() as $err)
                        {
                            array_push($message,[$err[0]. ' on line '.$key]);
                        }
                        $success=false;
                    }

                    $chkEmail=$csv->checkEmail($data, $user_email,$all_data, $columns['email'], $key, $success,'email');
                    $message=array_merge($message, $chkEmail[0]);
                    $success=$chkEmail[1];

                    // citizen
                    if(isset($data[$columns['citizen_number']])){
                        $chkCitizen=$csv->checkCitizen($data, $user_citizen,$all_data, $columns['citizen_number'], $key, $success,'citizen number');
                        $message=array_merge($message, $chkCitizen[0]);
                        $success=$chkCitizen[1];
                    }

                    if(isset($data[$columns['symbol_number']])){
                        $checkSymbol=$csv->checkSymbol($data, $user_symbol,$all_data, $columns['symbol_number'], $key, $success,'symbol number');
                        $message=array_merge($message, $checkSymbol[0]);
                        $success=$checkSymbol[1];
                    }
                    
                    array_push($all_data,$data);

                }else{
                    if(!$this->checkIntegrity($data,$columns)){
                        $success=false;
                        array_push($message,['Data should be on correct order. Please try again']);
                        array_push($message,['#,email,name,gender,phone,address, citizen_number(optional), symbol_number(optional)']);
                        break;
                    }
                   
                }
            }
        }
        if($success){
            array_push($message,['All Set']);
        }
        $data_fix=$this->jsonEncode($all_data) ;
        return response()->json(['success'=>$success,'message'=>$message,'data'=>$data_fix]);
    }

    public function getRule($columns)
    {
        return [
            $columns['#']=>'',
            // email
            $columns['email']=> 'required|email',
            
            // name
            $columns['name']=> 'required|max:150',
            
            // gender
            $columns['gender']=>'required',

            // phone
            $columns['phone'] => 'required|numeric|digits_between:1,11',

            // address
            $columns['address'] => 'required|max:150',

            
        ];
    }

    public function getErr($columns)
    {
        return [
            // email
            $columns['email'].'.email'=>'Invalid Email',
            $columns['email'].'.required'=>'Email is required',
            // name
            $columns['name'].'.required'=>'Name is required',
            $columns['name'].'.max'=>'Maximum charater of name is 150',
            // gender
            $columns['gender'].'.required'=>'Gender is required',
            
            // phone
            $columns['phone'].'.required'=>'Phone is required',
            $columns['phone'].'.numeric'=>'Phone should be numeric',
            $columns['phone'].'.digits_between'=>'Maximum charater for phone is 11',
            
            // address
            $columns['address'].'.required'=>'Address is required',
            $columns['address'].'.max'=>'Maximum charater of address is 150',

        ];
    }

    public function checkIntegrity($data,$columns)
    {
        {
            if(count($data)>=6 && count($data)<=count($columns)){
                if($data[$columns['#']]=='#' && $data[$columns['email']]=='email' && $data[$columns['name']]=='name' 
                && $data[$columns['gender']]=='gender' && $data[$columns['phone']]=='phone' 
                && $data[$columns['address']]=='address'){
                    return true;
                }
            }
            
            return false;
        }
    }

    public function getColumnIntegrity()
    {
        return array(
            '#'=>0,
            'email'=>1,
            'name'=>2,
            'gender'=>3,
            'phone'=>4,
            'address'=>5,
            'citizen_number'=>6,
            'symbol_number'=>7,
        );
    }

    /**
     * Encode array from latin1 to utf8 recursively
     * @param $dat
     * @return array|string
     */
    public static function convert_from_latin1_to_utf8_recursively($dat)
    {
        return $dat;
        if (is_string($dat)) {
            return utf8_encode($dat);
        } elseif (is_array($dat)) {
            $ret = [];
            foreach ($dat as $i => $d) $ret[ $i ] = self::convert_from_latin1_to_utf8_recursively($d);

            return $ret;
        } elseif (is_object($dat)) {
            foreach ($dat as $i => $d) $dat->$i = self::convert_from_latin1_to_utf8_recursively($d);

            return $dat;
        } else {
            return $dat;
        }
    }

    private function jsonEncode($arr) {
        $str = '{';
        $count = count($arr);
        $current = 0;
    
        foreach ($arr as $key => $value) {
            $str .= sprintf('"%s":', $this->sanitizeForJSON($key));
    
            if (is_array($value)) {
                $str .= '[';
                foreach ($value as &$val) {
                    $val = $this->sanitizeForJSON($val);
                }
                $str .= '"' . implode('","', $value) . '"';
                $str .= ']';
            } else {
                $str .= sprintf('"%s"', $this->sanitizeForJSON($value));
            }
    
            $current ++;
            if ($current < $count) {
                $str .= ',';
            }
        }
    
        $str.= '}';
    
        return $str;
    }
    private function sanitizeForJSON($str)
    {
        // Strip all slashes:
        $str = stripslashes($str);

        // Only escape backslashes:
        $str = str_replace('"', '\"', $str);

        return $str;
    }
    
}
