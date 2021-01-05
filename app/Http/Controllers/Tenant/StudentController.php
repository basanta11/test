<?php

namespace App\Http\Controllers\Tenant;

use App\User;
use App\Section;
use App\Classroom;
use App\Helpers\CsvHelper\CsvValidation;
use App\Helpers\Datatable\StudentQuery;
use App\StudentDetail;
use App\Mail\UserCreated;
use App\Helpers\FileHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\TeacherDataUpdated;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $classrooms=Classroom::whereStatus(1)->get();
        return view('admin.students.index',compact('classrooms'));
    }

    public function studentListForIndex(StudentQuery $query, Request $request, FileHelper $file)
    {
        $data = $mainData = [];
        $currentPage = $request->all();
        $columnsToSearch = ['name', 'email', 'phone','symbol_number'];

        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage['pagination']['page'];
        });
		$customQuery = User::query();

        $filteredQuery = $query->prepareQuery($customQuery, $columnsToSearch);
        $mainData= $filteredQuery
        ->with(['student_detail','student_detail.classroom','student_detail.section','created_user'])
        ->whereRoleId(4)
        ->paginate(10);


        $data['data'] = $mainData->map(function($data,$sn) use($file){
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
            $classroom=$data['student_detail'] ? ($data['student_detail']['classroom'] ? $data['student_detail']['classroom']['title'] : 'N/A') : 'N/A';
            $section=$data['student_detail'] ? ($data['student_detail']['section'] ? $data['student_detail']['section']['title'] : 'N/A') : 'N/A';
            return [
                'sn'=>$sn+=1,
                'id'=>$data->id,
                'name'=>$data->name,
                'email'=>$data->email,
                'address'=>$data->address,
                'phone'=>$data->phone,
                'citizen_number'=>$data->citizen_number,
                'symbol_number'=>$data->symbol_number,
                'classroom'=>$classroom.', '.$section,
                'roll_number'=>$data['student_detail'] ? $data['student_detail']['roll_number'] : 'N/A',
                'dob'=>$data['student_detail'] ? $data['student_detail']['dob'] : 'N/A',
                'guardian'=>$data['student_detail'] ? [
                    'Name'=>$data['student_detail']['guardian_name'],
                    'Phone'=>$data['student_detail']['guardian_number'],
                    'Email'=>$data['student_detail']['guardian_email']
                    ] : 'N/A',
                'gender'=>$g,
                'status'=>$status,
                'created_by'=>isset($data['created_user']['name'] ) ? $data['created_user']['name'] : "",
                'image'=>($data->image && $file->fileExists('users',$data->image)) ? Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/users/'.$data->image) : global_asset('assets/media/users/default.jpg'),
                'created_at'=>$data->created_at,
            ];
        });

        $pagination = $mainData->toArray();
        $data = array_merge($data, [
            "meta" => [
                "page" => $pagination['current_page'],
                "pages" => $pagination['last_page'],
                "perpage" => $pagination['per_page'],
                "total" => $pagination['total']
            ]
        ]);
        
        return $data;

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $classrooms=Classroom::whereStatus(1)->get();
        return view('admin.students.create',compact('classrooms'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,FileHelper $file)
    {
        //
        request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'gender' => ['required', 'integer'],
            // 'phone' => ['required', 'integer'],
            'address' => ['required', 'string', 'max:255'],
            // 'guardian_name' => ['required', 'string', 'max:255'],
            // 'guardian_email' => ['required', 'string', 'email', 'max:255','unique:App\User,email'],
            // 'guardian_number' => ['required', 'integer'],
            'roll_number' => ['required', 'integer'],
            'classroom_id'=>['required','integer'],
            'section_id'=>['required','integer'],
            'dob'=>['required'],
            // 'citizen_number' => ['required','unique:users'],
            'symbol_number' => ['required', 'numeric','unique:users'],
        ]);
        
        $password = Str::random(8);
        $request->merge([
            'role_id' => 4,
            'password' => bcrypt($password),
            'status' => 0
        ]);

        if ( $request->image ) {
            $image=$file->storeFile($request->image,'users');
        }
        else {
            $image = null;
        }
        
        $user = User::create(array_merge(['image'=>$image,'created_by'=>auth()->user()->id],$request->only(
            ['name','email','gender','phone','address','role_id','password','status','citizen_number','symbol_number','house_number']
        )));

        if ( tenant()->plan == 'large') {
            // create guardian
            $guardian = $this->createGuardian($request->guardian_name, $request->guardian_email, $request->guardian_number);
    
            // student detail
            StudentDetail::create(array_merge(
                [
                    'user_id' => $user->id,
                    'guardian_id' => $guardian->id,
                ],$request->only([
                    'guardian_name','guardian_email','guardian_number','classroom_id','section_id','roll_number','dob',
                ])
            ));
            // student detail end
        }
        else {
            // student detail
            StudentDetail::create(array_merge(
                [
                    'user_id' => $user->id,
                ],$request->only([
                    'classroom_id','section_id','roll_number','dob',
                ])
            ));
            // student detail end
        }

        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['password'] = $password;

        Mail::to($user)->queue(new UserCreated($data));

        return redirect('/students')->with('success', 'Data saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id,FileHelper $file)
    {
        //
        $student=User::with(['student_detail','student_detail.classroom','student_detail.section'])->findOrFail($id);
        if($student->role_id!=4)
            abort(403);
        $profile_image=($student->image && $file->fileExists('users',$student->image)) ? Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/users/'.$student->image) : global_asset('assets/media/users/default.jpg');
                
        return view('admin.students.show',compact('student','profile_image'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        //
        $student=User::with(['student_detail','student_detail.classroom'])->findOrFail($id);
        if($student->role_id!=4)
            abort(403);
        
        $classrooms=Classroom::whereStatus(1)->get();
        $sections=null;
        if(isset($student['student_detail']['classroom']))
            $sections=Section::with(['classroom'])->whereHas('classroom',function($q){
                $q->whereStatus(1);
            })->whereClassroomId($student['student_detail']['classroom']['id'])->get();
        // dd($sections);
        return view('admin.students.edit',compact('student','classrooms','sections'));
    }

    public function behaviorControl(Request $request,$id)
    {
        $user=User::findOrFail($id);
        // dd($user->student_detail);
        request()->validate([
            'behavior'=>'required|max:500',
        ]);
        if(!empty($user->student_detail)){
            $user->student_detail->update(['behavior'=>$request->behavior]);
            return back()->with('success','Successfully updated.');
        }else{
            abort(403);
        }
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
        //
        $student=User::findOrFail($id);
        if($student->role_id!=4)
            abort(403);
        $student_detail=StudentDetail::whereUserId($student->id)->first();
                request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email, '.$student->id],
            'gender' => ['required', 'integer'],
            // 'phone' => ['required', 'integer'],
            'address' => ['required', 'string', 'max:255'],
            // 'guardian_name' => ['required', 'string', 'max:255'],
            // 'guardian_email' => ['required', 'string', 'email', 'max:255'],
            // 'guardian_number' => ['required', 'integer'],
            'roll_number' => ['required', 'integer'],
            'classroom_id'=>['required','integer'],
            'section_id'=>['required','integer'],
            'dob'=>['required'],
            // 'citizen_number' => ['required','unique:users,citizen_number, '.$student->id],
            'symbol_number' => ['required', 'numeric','unique:users,symbol_number, '.$student->id],
        ]);
        
        $image=$student->image;
        if(isset($request->image)){
            $image=$file->updateFile($request->image,'users',$student->image);
        };

        $student->update(array_merge(['image'=>$image],$request->only(
            ['name','email','gender','phone','address','role_id','citizen_number','symbol_number', 'house_number']
        )));
        // $image=$file->storeFile($request->image,'users');
        
        // $user = User::create(array_merge(['image'=>$image],$request->only(
        //     ['name','email','gender','phone','address','role_id','password','status']
        // )));

        // student detail
        if($student_detail){
            $student_detail->update($request->only([
                    'guardian_name','guardian_email','guardian_number','classroom_id','section_id','roll_number','dob',
                ]
            ));
        }
        
        // student detail end

        // Send email to student if principal/admin updates data
        $data['name'] = $student->name;
        $data['message'] = 'Your personal details have been updated.';

        Mail::to($student)->queue(new TeacherDataUpdated($data));
        
        return redirect('/students')->with('success', 'Data updated.');
        
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
        $message=$status==1 ? 'Student activated.': 'Student deactivated' ;
        $student=User::findOrFail($id);

        $student->update(['status'=>$status]);

        // Send email to student after status update
        $data['name'] = $student->name;
        $result = $status == 1 ? 'activated.' : 'deactivated.';
        $data['message'] = 'Your account has been ' . $result;

        Mail::to($student)->queue(new TeacherDataUpdated($data));

        return back()->with('success',$message);
     }
 
     // $status -> send 1 to active, 2 to deactivate
     public function statusControlBulk(Request $request,$status)
     {
        if(!in_array($status,[1,2]) || !json_decode($request->user_list)){
            return back()->with('error','Action cannot be recognized. Please try again.');
        }

        $message=$status==1 ? 'Student(s) activated.': 'Student(s) deactivated' ;
        User::whereIn('id',json_decode($request->user_list))->update(['status'=>$status]);

        // Send email to students after status update
        $updatedStudents = User::whereIn('id',json_decode($request->user_list))->get();
        foreach ($updatedStudents as $student) {
            $data['name'] = $student->name;
            $result = $status == 1 ? 'activated.' : 'deactivated.';
            $data['message'] = 'Your account has been ' . $result;

            Mail::to($student)->queue(new TeacherDataUpdated($data));
        }

        return back()->with('success',$message);
 
     }

    public function createBulk()
    {
        $classrooms=Classroom::whereStatus(1)->get();
        return view('admin.students.create-bulk',compact('classrooms'));
    }

    public function storeBulk(Request $request)
    {
        if(!$request->data_fix)
            return back()->with('error','There was an error processing data. Please try again.');
        // $dataFix=array();
        // $dataFixStudentDetails=array();
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
            $student=User::create(
                [
                    'email'=>$data[$columns['email']],
                    'name'=>$data[$columns['name']],
                    'gender'=>$g,
                    'phone'=>$data[$columns['phone']],
                    'address'=>$data[$columns['address']],
                    'citizen_number'=>isset($data[$columns['citizen_number']]) ? $this->maskCitizen($data[$columns['citizen_number']]) : null,
                    'symbol_number'=>isset($data[$columns['symbol_number']]) ? ($data[$columns['symbol_number']] ? $data[$columns['symbol_number']] : null ) : null,
                    'role_id' => 4,
                    'password' => bcrypt($password),

                    'created_by'=>auth()->user()->id,
                    'status' => 0,
                ]);
            
            if ( tenant()->plan == 'large') {

                // create guardian
                $guardian = $this->createGuardian($data[$columns['guardian_name']], $data[$columns['guardian_email']], $data[$columns['guardian_phone']]);

                StudentDetail::create(
                [
                    'user_id'=>$student->id,
                    'guardian_id'=>$guardian->id,
                    'guardian_email'=>$data[$columns['guardian_email']],
                    'guardian_name'=>$data[$columns['guardian_name']],
                    'guardian_number'=>$data[$columns['guardian_phone']],
                    'dob'=>$data[$columns['dob']],
                    'classroom_id'=>$request->classroom_id,
                    'section_id'=>$request->section_id,
                    // comment this if not needed
                    'roll_number'=>'',
                ]);    
            }
            else {
                StudentDetail::create(
                [
                    'user_id'=>$student->id,
                    'dob'=>$data[$columns['dob']],
                    'classroom_id'=>$request->classroom_id,
                    'section_id'=>$request->section_id,
                    // comment this if not needed
                    'roll_number'=>'',
                ]);
            }

            array_push($emailData,[
                'email'=>$data[$columns['email']],
                'password'=>$password,
                'name'=>$data[$columns['name']],
            ]);
        }
      

        foreach($emailData as $user){
            Mail::to($user['email'])->queue(new UserCreated($user));
        }
        
        
        return redirect('/students')->with('success', 'Data upload successful.');
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
        $user=User::with(['student_detail'])->get();

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


                    if ( tenant()->plan == 'large') {

                        $chkEmail=$csv->checkEmail($data, $user_email,$all_data, $columns['guardian_email'], $key, $success,'guradian email');
                        $message=array_merge($message, $chkEmail[0]);
                        $success=$chkEmail[1];
                    }
                    // citizen
                    if(isset($data[$columns['citizen_number']])){
                        $chkCitizen=$csv->checkCitizen($data, $user_citizen,$all_data, $columns['citizen_number'], $key, $success,'citizen nubmer');
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
                        if(tenant()->plan=="large"){

                            array_push($message,['#,email,name,gender,phone,address, guardian_email, guardian_name, guardian_phone, dob,citizen_number(optional), symbol_number(optional)']);
                        }else{
                            array_push($message,['#,email,name,gender,phone,address, dob,citizen_number(optional), symbol_number(optional)']);
                        }
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

        /**
     * Encode array from latin1 to utf8 recursively
     * @param $dat
     * @return array|string
     */
    public static function convert_from_latin1_to_utf8_recursively($dat)
    {
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


    public function getRule($columns)
    {
        if(tenant()->plan=='large'){

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
    
                // guardian email
                $columns['guardian_email']=> 'required|email',
                
                // guardian name
                $columns['guardian_name']=> 'required|max:150',
                
                // guardian phone
                $columns['guardian_phone'] => 'required|numeric|digits_between:1,11',
    
                // dob
                $columns['dob']=>'required|date'
                
            ];
        }else{
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
    
    
                // dob
                $columns['dob']=>'required|date'
                
            ];
        }
    }

    public function getErr($columns)
    {
        if(tenant()->plan=='large'){

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
    
                // guradian email
                $columns['guardian_email'].'.email'=>'Invalid Email',
                $columns['guardian_email'].'.required'=>'Guardian Email is required',
    
                // guardian name
                $columns['guardian_name'].'.required'=>'Guardian Name is required',
                $columns['guardian_name'].'.max'=>'Maximum charater of name is 150',
                
                // guardian phone
                $columns['guardian_phone'].'.required'=>'Guardian Phone is required',
                $columns['guardian_phone'].'.numeric'=>'Phone should be numeric',
    
                // dob
                $columns['dob'].'.required'=>'Date of birth is required',
                $columns['dob'].'.date'=>'Date should be on yyyy-mm-dd format',
            ];
        }else{
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
    
                // dob
                $columns['dob'].'.required'=>'Date of birth is required',
                $columns['dob'].'.date'=>'Date should be on yyyy-mm-dd format',
            ];
        }
    }

    public function checkIntegrity($data , $columns)
    {

        if(tenant()->plan=='large'){
            if(count($data)>=6 && count($data)<=count($columns)){
                if($data[$columns['#']]=='#' && $data[$columns['email']]=='email' && $data[$columns['name']]=='name' 
                && $data[$columns['gender']]=='gender' && $data[$columns['phone']]=='phone' 
                && $data[$columns['address']]=='address' && $data[$columns['guardian_email']]=='guardian_email' 
                && $data[$columns['guardian_name']]=='guardian_name'  && $data[$columns['guardian_phone']]=='guardian_phone' 
                && $data[$columns['dob']]=='dob'){
                    return true;
                }
            }
        }else{
            if(count($data)>=6 && count($data)<=count($columns)){
                if($data[$columns['#']]=='#' && $data[$columns['email']]=='email' && $data[$columns['name']]=='name' 
                && $data[$columns['gender']]=='gender' && $data[$columns['phone']]=='phone' 
                && $data[$columns['address']]=='address' 
                && $data[$columns['dob']]=='dob'){
                    return true;
                }
            }   
        }
        
        return false;
    }

    public function getColumnIntegrity()
    {
        if(tenant()->plan=='large'){
            return array(
                '#'=>0,
                'email'=>1,
                'name'=>2,
                'gender'=>3,
                'phone'=>4,
                'address'=>5,
                'guardian_email'=>6,
                'guardian_name'=>7,
                'guardian_phone'=>8,
                'dob'=>9,
                'citizen_number'=>10,
                'symbol_number'=>11,
            );

        }else{
            return array(
                '#'=>0,
                'email'=>1,
                'name'=>2,
                'gender'=>3,
                'phone'=>4,
                'address'=>5,
                'dob'=>6,
                'citizen_number'=>7,
                'symbol_number'=>8,
            );
        }
    }

    public function getStudentsFromSection($id)
    {
        $students = StudentDetail::with(['user'])->whereHas('user',function($q){
            $q->whereStatus(1);
        })->where('section_id', $id)->with(['user'])->get();

        return response()->json(['students' => $students]);
    }

    public function createGuardian($name, $email, $phone)
    {
        $password = Str::random(8);

        $guardian = User::create([
            'name' => $name,    
            'email' => $email,
            'phone' => $phone,
            'role_id' => 5,
            'gender' => 2,
            'password' => bcrypt($password)
        ]);

        $data['name'] = $name;
        $data['email'] = $email;
        $data['password'] = $password;

        Mail::to($guardian)->queue(new UserCreated($data));

        return $guardian;
    }
}
