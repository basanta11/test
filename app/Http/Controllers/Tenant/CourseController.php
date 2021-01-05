<?php

namespace App\Http\Controllers\Tenant;

use App\User;
use App\Course;
use App\Lesson;
use App\Section;
use App\Classroom;
use App\CourseDetail;
use App\Helpers\FileHelper;
use Illuminate\Http\Request;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FileHelper $file)
    {
        $sn = 0;
    
        // $courses = Course::with(['course_details.user','course_details.section','classroom'])->get();
           
        $courses = Course::with(['course_details.user','course_details.section','classroom'])->get()->map(function($data, $sn) use($file) {
            
            $classroom=$data['classroom'] ? $data['classroom']['title'] : 'N/A';

            $sections=isset($data['course_details']) ? $data['course_details']->map(function($q){
                return $q->section;
            })->count() : 0;

            $users=isset($data['course_details']) ? $data['course_details']->map(function($q){
                return $q->user;
            }) : null;
            
            return [
                'sn' => $sn += 1,
                'id' => $data->id,
                'title' => $data->title,
                'credit_hours' => $data->credit_hours,
                'learn_what' => $data->learn_what,
                'classroom'=>$classroom,
                'teachers'=>$users,
                'description'=>$data->description,
                'sections'=>$sections,
                'status' => $data->status == 1 ? 11 : 10,
                'created_at' => $data->created_at,
                'image'=>($data->image && $file->fileExists('courses',$data->image)) ? Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/courses/'.$data->image) : global_asset('assets/media/emails/email_logo.png'),

            ];
        });
        // dd($courses);
        
        return view('admin.courses.index', compact('courses'));
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
        return view('admin.courses.create',compact('classrooms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createAssignedTeacher($id)
    {
        //
        $course=Course::with(['course_details.user','course_details','course_details.section'])->findOrFail($id);
        $hasTeachers=!$course['course_details']->isEmpty();
        if($hasTeachers){
            $sections=$course['course_details']->groupBy('section_id')->map(function($k,$q){
                return [
                    'id'=>$q,
                    'title'=>$k[0]['section']['title'],
                    'teachers'=>$k->map(function($t){
                        return $t['user']['id'];
                    })->toArray(),
                ];
            });
            
        }else{
            $sections=$course['classroom']['sections'];
        }
        // dd($sections);
        $teachers=User::whereStatus(1)->whereRoleId(3)->get();
        
        return view('admin.courses.assign-teacher-create',compact('course','teachers','hasTeachers','sections'));
    }

    public function updateAssignedTeacher($id,Request $request, NotificationHelper $notify)
    {
        $course=Course::with(['classroom.sections','course_details','course_details.section'])->findOrFail($id);
        $hasTeachers=!$course['course_details']->isEmpty();
        
        $old=isset($course['course_details']) ? $course['course_details']->map(function($q){
            return $q->user_id;
        })->toArray() : [];
        if($hasTeachers){
            $course->course_details()->delete();

        }
        $arr=array();
        foreach($request->section_id as $key=>$s)
        {
            foreach($s as $users)
                array_push($arr,['course_id'=>$id,'section_id'=>$key,'user_id'=>$users,'status'=>1]);

            if(tenant()->plan === 'large'){
                if(!in_array($users, $old)){
                    $notify->notifyOne(User::findOrFail($users), 'App\Course',$course->id, '/assigned-courses/'.$course->id, auth()->user()->name. ' has assigned you to a course. Titie: '.$course->title);
                }
            }
        }

        $course->course_details()->createMany($arr);
        $section_id=($course->course_details()->get()->map(function($q){
            return 
                $q->section->id;
        }));
    
        $users_section=User::with('student_detail')->whereHas('student_detail',function($q) use($section_id){
            $q->whereIn('section_id',$section_id);
        })->get();

        if(tenant()->plan === 'large'){
            if(!$hasTeachers){
                $notify->notifyMany(
                    $users_section,
                    'App\Meeting',
                    $course->id,
                    '/student/assigned-courses/'.$course->id,
                    auth()->user()->name . ' has added a new course. Title: '.$course->title
                );
            }
        }

        return redirect('/courses')->with('success','Data assign successful.');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, FileHelper $file)
    {
        //
        request()->validate([
            'title'=>'required|max:150',
            'credit_hours'=>'required|max:999|numeric',
            'learn_what'=>'required|max:500',
            'description'=>'required|max:500',
            'classroom_id'=>'required',
        ]);

        if ( $request->photo ) {
            $image=$file->storeFile($request->photo, 'courses');
        }
        else {
            $image = null;
        }

        $request->merge(['image' => $image]);

        $course=Course::create(array_merge(['status'=>1],$request->only(['title','credit_hours','description','learn_what','classroom_id','image'])));
        
        return redirect('/courses')->with('success','Data saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show($id,FileHelper $file)
    {
        //
        $sn=0;
        $course=Course::with(['course_details.user','course_details','course_details.section'])->findOrFail($id);
        $lessons=Lesson::with(['topics'])->whereCourseId($course->id)->get()->map(function($data, $sn) {

            
            return [
                'sn' => $sn += 1,
                'id' => $data->id,
                'title' => $data->title,
                'brief' => $data->brief,
                'topics'=>$data['topics']->count(),
                'status' => $data->status == 1 ? 11 : 10,
                'created_at' => $data->created_at,
            ];
        });
        return view('admin.courses.show',compact('course','lessons','file'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $course=Course::with(['classroom'])->whereId($id)->first();
        
        // $users=array();
        // $classrooms=Classroom::whereStatus(1)->get();
        // $sections=null;
        // // dd($course['course_details']);
        // if(isset($course['course_details'][0]['classroom']))
        //     $sections=Section::whereClassroomId($course['course_details'][0]['classroom']['id'])->get();
        // foreach($course['course_details'] as $cd)
        // {
        //     array_push($users,$cd['user_id']);
        // }

        // $teachers=User::whereStatus(1)->whereRoleId(3)->get();
        return view('admin.courses.edit',compact('course'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course, FileHelper $file)
    {
        //
        request()->validate([
            'title'=>'required|max:150',
            'credit_hours'=>'required|max:999|numeric',
            'learn_what'=>'required|max:500',
            'description'=>'required|max:500',
        ]);

        $image=$course->image;
        if(isset($request->photo)){
            $image=$file->updateFile($request->photo,'courses',$course->image);
        };

        $request->merge(['image' => $image]);

        $course->update($request->only(['title','description','credit_hours','learn_what','image']));


        return redirect('/courses')->with('success','Data updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        //
    }
    // $status -> send 1 to active, 2 to deactivate
    public function statusControl($id,$status)
    {
        if(!in_array($status,[0,1])){
            return back()->with('error','Action cannot be recognized. Please try again.');
        }
        $message=$status==1 ? 'Course activated.': 'Course deactivated' ;
        $course=Course::findOrFail($id);

        $course->update(['status'=>$status]);
        CourseDetail::where('course_id', $course->id)->update(['status' => $status]);

        return back()->with('success',$message);
    }

    // $status -> send 1 to active, 2 to deactivate
    public function statusControlBulk(Request $request,$status)
    {
        if(!in_array($status,[0,1]) || !json_decode($request->list)){
            return back()->with('error','Action cannot be recognized. Please try again.');
        }

        $message=$status==1 ? 'Course(s) activated.': 'Course(s) deactivated' ;
        $courses = Course::whereIn('id',json_decode($request->list))->update(['status'=>$status]);
        CourseDetail::whereIn('course_id', json_decode($request->list))->update(['status' => $status]);

        return back()->with('success',$message);
    }

    public function getTeachers(Course $course)
    {
        $teachers = User::whereIn('id', $course->course_details->pluck('user_id'))->select('id','name')->get();

        return response()->json($teachers);
    }
}
