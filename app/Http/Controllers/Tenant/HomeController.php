<?php

namespace App\Http\Controllers\Tenant;

use Str;
use Hash;
use App\User;
use App\Event;
use App\Course;
use App\Frontend;
use App\Schedule;
use App\CourseDetail;
use App\StudentDetail;
use App\Helpers\FileHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\UserSectionBehaviour;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    //
   
    
    public function home(FileHelper $file)
    {
        $principal = User::where('role_id', 1)->first();
        $events = Event::where('event_date', '>', date('Y-m-d'))->where('status', 1)->limit(5)->orderByDesc('event_date')->get();

        $frontend=Frontend::first();
     
        if ( auth()->user()->hasRole('Student') ) {
            $image = (auth()->user()->image && $file->fileExists('users',auth()->user()->image)) ? Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/users/'.auth()->user()->image) : global_asset('assets/media/users/default.jpg');

            $classmates = User::where('status', '!=', 2)->where('id', '!=', auth()->user()->id)->whereHas('student_detail', function ($q) {
                $q->where('section_id', auth()->user()->student_detail->section_id);
            })->get()->map(function($data) use($file){
                return [
                    'id'=>$data->id,
                    'name'=>$data->name,
                    'email'=>$data->email,
                    'image'=>($data->image && $file->fileExists('users',$data->image)) ? Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/users/'.$data->image) : global_asset('assets/media/users/default.jpg'),
                ];
            });

            $schedule = Schedule::where('section_id', auth()->user()->student_detail->section_id)->where('day', strtolower(date('l')))->orderBy('end_time')->with('course')->get();
            // dd($schedule->toArray());

            $teachers = User::where('status', '!=', 2)->whereHas('course_details', function ($q) {
                $q->where('section_id', auth()->user()->student_detail->section_id);
            })->with(['course_details.course'])->get()->map(function($data) use($file){
                $cd = CourseDetail::where('user_id', $data->id)->where('section_id', auth()->user()->student_detail->section_id)->with(['course'])->get()->toArray();

                return [
                    'id'=>$data->id,
                    'name'=>$data->name,
                    'courses'=>$cd,
                    'image'=>($data->image && $file->fileExists('users',$data->image)) ? Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/users/'.$data->image) : global_asset('assets/media/users/default.jpg'),
                ];
            });
            // dd($teachers);

            return view('students.home', compact('classmates', 'principal', 'image', 'events', 'teachers', 'schedule'));
        }
        else if ( auth()->user()->hasRole('Teacher') ) {
            $image = (auth()->user()->image && $file->fileExists('users',auth()->user()->image)) ? Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/users/'.auth()->user()->image) : global_asset('assets/media/users/default.jpg');

            $schedule = Schedule::with(['section','section.classroom','section.course_details.user','course'])
                ->where('day', strtolower(date('l')))
                ->whereNotNull('course_id')
                ->whereHas('section.course_details',function($q){
                    $q->where('user_id',auth()->user()->id);
                })->orderBy('start_time')->get();

            $courses = CourseDetail::where('user_id', auth()->user()->id)->where('status', 1)->with(['course', 'section.classroom'])->orderByDesc('created_at')->limit(3)->get();
            // dd($courses->toArray());

            return view('teachers.home', compact('image', 'events', 'schedule', 'courses'));
        }
        else if ( auth()->user()->hasRole('Principal') || auth()->user()->hasRole('Administrator') ) {
            $teachers = User::with(['created_user'])->whereRoleId(3)->get()->map(function($data,$sn) use($file){
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

            $courses = Course::with(['course_details.section','classroom'])->orderBy('created_at', 'desc')->limit(3)->get()->map(function($data) {
            
                $classroom=$data['classroom'] ? $data['classroom']['title'] : 'N/A';
    
                $sections=isset($data['course_details']) ? $data['course_details']->map(function($q){
                    return $q->section;
                })->count() : 0;
                
                return [
                    'id' => $data->id,
                    'title' => $data->title,
                    'credit_hours' => $data->credit_hours,
                    'classroom'=>$classroom,
                    'sections'=>$sections,
                ];
            });

            if ( auth()->user()->hasRole('Principal') ) {
                $administrators=User::with(['created_user'])->whereRoleId(2)->get()->map(function($data,$sn) use($file){
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

                return view('home', compact('principal', 'teachers', 'administrators', 'courses', 'events'));
            }

            return view('home', compact('principal', 'teachers', 'courses', 'events'));
        }
        else {
            $student = StudentDetail::where('guardian_id', auth()->user()->id)->first();

            $schedule = Schedule::where('section_id', $student->section_id)->where('day', strtolower(date('l')))->orderBy('end_time')->with('course')->get();

            $behaviours = UserSectionBehaviour::where('user_id', $student->user_id)->with(['teacher', 'section_behaviour.behaviour_type'])->get();
            // dd($behaviours);

            return view('guardians.home', compact('events', 'schedule', 'behaviours'));
        }
    }
    public function changePassword()
    {
        return view('change-password');

    }

    public function updatePassword(Request $request)
    {
        request()->validate([
            'password'=>'required|min:8'
        ]);
        if(!$this->isPasswordMatched($request)){
            return back()->with('error','Password didnot match.');
        }
        $arr=['password'=>bcrypt($request->password)];
        if(auth()->user()->status!=2)
            $arr=array_merge($arr,['status'=>1]);
        User::whereId(auth()->user()->id)->update($arr);
        return redirect('/app')->with('success','Password updated successfully.');
        
    }

    public function isPasswordMatched(Request $request)
    {
        return response()->json(Hash::check($request->old_password, auth()->user()->password));
    }

    public function storeFile(Request $request, FileHelper $file)
    {
        $getFile = $_FILES['selectedFile'];
        // return response()->json([ 'test' => $getFile ]);
        // return response()->json(['test' => $request->file('selectedFile')]);

        if ( isset($getFile) && !empty($getFile) ) {
            // $file->storeFile( $getFile, 'videos' );
            $info = pathinfo($getFile['name']);
            $fileName = 'videos-'. time() . '.' . $info['extension'];

            Storage::disk(config('app.storage_driver'))->putFileAs(
                'videos', $getFile['tmp_name'], $fileName
            );
        }
        else {
            return response()->json(['result' => 'failure']);
        }

        return response()->json(['result' => 'success']);
    }

    public function changeColor(Request $request)
    {
        // dd($request->all());
        $f=Frontend::first();
        if($f){
            if($request->type=="primary")
                $f->update(['primary_color'=>$request->color]);
            else
                $f->update(['secondary_color'=>$request->color]);
        }else{
            if($request->type=="primary")
                Frontend::create(['user_id'=>auth()->user()->id,'primary_color'=>$request->color]);
            else
                Frontend::create(['user_id'=>auth()->user()->id,'secondary_color'=>$request->color]);
        }
        return response()->json(['success'=>true]);
    }
    
    public function addBanner(Request $request, FileHelper $file)
    {
        $f=Frontend::first();
        if($f){
            if($request->image){
               $image=$file->storeFile($request->image,'frontend');
            }
            $old=json_decode($f->banners,true);
            if($old){
                $f->update(['banners'=>json_encode(array_merge($old,[
                    strval(Str::uuid())=>[
                        'title'=>$request->title,
                        'description'=>$request->description,
                        'image'=>$image,
                    ]
                ]))]);
            }else{
                $f->update(['banners'=>json_encode([
                    strval(Str::uuid())=>[
                        'title'=>$request->title,
                        'description'=>$request->description,
                        'image'=>$image,
                    ]
                ])]);
            }
        }else{
            if($request->image){
                $image=$file->storeFile($request->image,'frontend');
            }
            Frontend::create(array_merge(['user_id'=>auth()->user()->id],['banners'=>json_encode([
                strval(Str::uuid())=>[
                    'title'=>$request->title,
                    'description'=>$request->description,
                    'image'=>$image,
                ]
            ])]));
        }
        
        return back()->with('success','Banner created');
    }
    public function changeBanner(Request $request,$id, FileHelper $file)
    {
        $f=Frontend::first();
        if(!$f)
            abort(404);
        $banners=json_decode($f->banners,true);
        $image=$banners[$id]['image'];
        if($request->image){
            $image=$file->updateFile($request->image,'frontend',$image);
        }
        unset($banners[$id]);
        $f->update(['banners'=>json_encode(array_merge($banners,[
            strval(Str::uuid())=>[
                'title'=>$request->title,
                'description'=>$request->description,
                'image'=>$image,
            ]
        ]))]);
        
        return back()->with('success','Banner updated');
    }
    public function changeMission(Request $request,FileHelper $file)
    {
        $f=Frontend::first();
        $arr=$request->only('mission');
        if($f){
            if($request->image){
                $arr=array_merge($arr,['mission_image'=>$file->updateFile($request->image,'frontend',$f->mission_image)]);
            }
            $f->update($arr);
        }else{
            if($request->image){
                $arr=array_merge($arr,['mission_image'=>$file->storeFile($request->image,'frontend')]);
            }
            Frontend::create(array_merge(['user_id'=>auth()->user()->id],$arr));
        }

        return back()->with('success','Mission Updated');

    }
    public function changeVision(Request $request, FileHelper $file)
    {
        $f=Frontend::first();
        $arr=$request->only('vision');
        if($f){
            if($request->image){
                $arr=array_merge($arr,['vision_image'=>$file->updateFile($request->image,'frontend',$f->vision_image)]);
            }
            $f->update($arr);
        }else{
            if($request->image){
                $arr=array_merge($arr,['vision_image'=>$file->storeFile($request->image,'frontend')]);
            }
            Frontend::create(array_merge(['user_id'=>auth()->user()->id],$arr));
        }

        return back()->with('success','Vision Updated');
    }
    public function changeGoal(Request $request, FileHelper $file)
    {
        $f=Frontend::first();
        $arr=$request->only('goal');
        if($f){
            if($request->image){
                $arr=array_merge($arr,['goal_image'=>$file->updateFile($request->image,'frontend',$f->goal_image)]);
            }
            $f->update($arr);
        }else{
            if($request->image){
                $arr=array_merge($arr,['goal_image'=>$file->storeFile($request->image,'frontend')]);
            }
            Frontend::create(array_merge(['user_id'=>auth()->user()->id],$arr));
        }

        return back()->with('success','Goal Updated');
    }

    public function deleteBanner($id, FileHelper $file)
    {
        $f=Frontend::first();
        if(!$f)
            abort(404);

        $banners=json_decode($f->banners,true);
        $image=$banners[$id]['image'];
        if($image){
            $image=$file->deleteFile('frontend',$image);
        }
        unset($banners[$id]);
        // dd($banners);
        $f->update(['banners'=>json_encode($banners)]);
        return back()->with('success','Banner deleted');

    }
}
