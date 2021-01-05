<?php

namespace App\Http\Controllers\Tenant;

use App\Course;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Lesson;
use App\User;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        //
        $course=Course::findOrFail($id);
        return view('admin.lessons.create',compact('course'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, NotificationHelper $notify)
    {
        //
        request()->validate([
            'title'=>'required|max:150',
            'brief'=>'required|max:500'
        ]);

        $course=Course::findOrFail($request->course_id);
        $lesson=Lesson::create(array_merge(['status'=>1],$request->only(['title','brief','course_id'])));
        $section_id=($course->course_details()->get()->map(function($q){
            return 
                $q->section->id;
            
        }));
    
        $users_section=User::with('student_detail')->whereHas('student_detail',function($q) use($section_id){
            $q->whereIn('section_id',$section_id);
        })->get();
        if(tenant()->plan==='large'){
            $notify->notifyMany(
                $users_section,
                'App\Lesson',
                $lesson->id,
                '/student/assigned-courses/'.$course->id,
                auth()->user()->name . ' has added a new lesson. Title: '.$lesson->title .', on Course: '.$course->title
            );
        }
        return auth()->user()->hasRole('Teacher') ? redirect('/assigned-courses/'.$request->course_id)->with('success','Data saved.') : redirect('/courses/'.$request->course_id)->with('success','Data saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $sn=0;
        $lesson=Lesson::with(['topics','course','topics.topic_attachments'])->findOrFail($id);

        if ( auth()->user()->hasRole('Teacher') && !empty($lesson->course->course_details) && !in_array(auth()->user()->id, $lesson->course->course_details->pluck('user_id')->toArray()) ) {
            abort(401);
        }

        $topics=$lesson['topics']->map(function($data, $sn) {
            return [
                'sn' => $sn += 1,
                'id' => $data->id,
                'title' => $data->title,
                'reference_links' => $data->reference_links ? "Yes" : "No",

                'attachments' => $data['topic_attachments']->count() > 0 ? "Yes" : "No",
                'video'=>($data->video || $data->video_url)!=null,
                'audio'=>$data->audio !=null,
                'image'=>$data->image !=null,
                'text'=>$data->text !=null,
                'status' => $data->status == 1 ? 11 : 10,
                'created_at' => $data->created_at,
            ];
        });
        return view('admin.lessons.show',compact('lesson','topics'));
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
        $lesson=Lesson::with(['course'])->findOrFail($id);

        if ( auth()->user()->hasRole('Teacher') && !empty($lesson->course->course_details) && !in_array(auth()->user()->id, $lesson->course->course_details->pluck('user_id')->toArray()) ) {
            abort(401);
        }

        return view('admin.lessons.edit',compact('lesson'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        request()->validate([
            'title'=>'required|max:150',
            'brief'=>'required|max:500'
        ]);
        $lesson=Lesson::with(['course'])->findOrFail($id);
        $lesson->update($request->only(['title','brief']));
        
        return auth()->user()->hasRole('Teacher') ? redirect('/assigned-courses/'.$lesson['course']['id'])->with('success','Data edited.') : redirect('/courses/'.$lesson['course']['id'])->with('success','Data edited.');
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
        if(!in_array($status,[0,1])){
            return back()->with('error','Action cannot be recognized. Please try again.');
        }
        $message=$status==1 ? 'Lesson activated.': 'Lesson deactivated' ;
        $lesson=Lesson::findOrFail($id);

        $lesson->changeStatus($status);

        return back()->with('success',$message);
    }

    // $status -> send 1 to active, 2 to deactivate
    public function statusControlBulk(Request $request,$status)
    {
        if(!in_array($status,[0,1]) || !json_decode($request->list)){
            return back()->with('error','Action cannot be recognized. Please try again.');
        }

        $message=$status==1 ? 'Lesson(s) activated.': 'Lesson(s) deactivated' ;
        $lessons = Lesson::whereIn('id',json_decode($request->list))->get()->map(function ($q) use($status){
            $q->changeStatus($status);
        });
        return back()->with('success',$message);
    }

    
}
