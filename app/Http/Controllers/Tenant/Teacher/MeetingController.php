<?php

namespace App\Http\Controllers\Tenant\Teacher;

use App\Course;
use App\CourseDetail;
use App\Helpers\FileHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Meeting;
use App\MeetingDetail;
use App\MeetingVideo;
use App\User;
use Arr;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Str;

class MeetingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $meetings=Meeting::with('course','course.classroom')->whereTeacherId(auth()->user()->id)->get()
        ->map(function($data,$sn){
            // dd($data['classroom']);
            return [
                'id'=>$data->id,
                'sn'=>$sn+=1,
                'title'=>$data->title,
                'course'=>$data['course']['title'],
                'status'=>$data->status+=10,
                'date'=>$data->start_date.' - '.$data->end_date,
                'time'=>$data->start_time.' - '.$data->end_time,
                'token'=>$data->token,
                'classroom'=>$data['course']['classroom']['title']
            ];
        });
        // dd($meetings);
        return view('teachers.meetings.index',compact('meetings'));
    }

    public function showVideo(Meeting $meeting, FileHelper $file)
    {
        $m=$meeting->videos()->get()->map(function($q,$sn) use($file){
            return [
                'sn'=>$sn+=1,
                'id'=>$q->id,
                'video'=>$q->video,
                'video_url'=>($q->video && $file->fileExists('meetings',$q->video)) ? Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/meetings/'.$q->video) : '',
                'created_at'=>date('jS M, Y g:i a', strtotime($q->updated_at)),
            ];
        });
        // dd($m);
        return view('teachers.meetings.show-video',compact('m'));
    }
    public function deleteVideo(MeetingVideo $meetingVideo, FileHelper $file)
    {
        $meeting_id=$meetingVideo->meeting_id;
        $file->deleteFile('meetings',$meetingVideo->video);
        $meetingVideo->delete();
        return redirect('/meetings/'.$meeting_id.'/saved-videos')->with('success','Successfully deleted');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $courses=Course::with('course_details')->whereHas('course_details',function($q){
            $q->whereUserId(auth()->user()->id);
        })->get();
        return view('teachers.meetings.create',compact('courses'));
    }

    public function getSections(Course $course)
    {
        $sections=CourseDetail::with(['section'])->whereCourseId($course->id)->whereUserId(auth()->user()->id)->get();
        return response()->json(['data'=>$sections]);
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
        $section_id=$request->section_id;
        $meeting=Meeting::create(array_merge(
            [
                'teacher_id'=>auth()->user()->id,
                'start_time'=>date('H:i:s',strtotime($request->start_time)),
                'end_time'=>date('H:i:s',strtotime($request->end_time)),
                'start_date'=>$request->start_date,
                'end_date'=>$request->end_date,
                'status'=>1,
                'token'=>$request->password,
            ]
        ,$request->only(['course_id','title'])));
        MeetingDetail::insert(array_map(function($data) use($meeting){
            return [
                'section_id'=>$data,
                'meeting_id'=>$meeting->id,
                'status'=>1,
            ];
        },$request->section_id));
        $users_section=User::with('student_detail')->whereHas('student_detail',function($q) use($section_id){
            $q->whereIn('section_id',$section_id);
        })->get();
        if(tenant()->plan==='large'){
            $notify->notifyMany(
                $users_section,
                'App\Meeting',
                $meeting->id,
                '/my-meetings/'.$meeting->id,
                auth()->user()->name . ' has added a new meeting. Title: '.$meeting->title
            );
        }
        return redirect('/meetings')->with('success','Data added');
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
        
        $meeting=Meeting::with(['course','teacher','meeting_details.section.classroom'])->findOrFail($id);
        if(auth()->user()->id!=$meeting->teacher_id)
            abort(403);
        $sections=null;
        try{
            $sections=implode(',',Arr::pluck($meeting['meeting_details'],'section.title'));

        }catch(Exception $e){
            $sections='N/A';
        }
        $jitsi_data=[
            'name'=>auth()->user()->name,
            'email'=>auth()->user()->email,
            'token'=>$meeting->token,
            'meeting_name'=>$meeting->token
            // 'meeting_name'=>
            //     $meeting['teacher']['name'].'-'.
            //     $meeting['meeting_details']->first()['section']['classroom']['title'].'-'.
            //     $sections.'-'.
            //     $meeting['course']['title']
            ,
            'image'=>(auth()->user()->image && $file->fileExists('users',auth()->user()->image)) ? Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/users/'.auth()->user()->image) : global_asset('assets/media/users/default.jpg'),
            // 'meeting_id'=>,s
        ];
        return view('teachers.meetings.show',compact('meeting','jitsi_data'));
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
        $meeting=Meeting::with(['course','meeting_details','meeting_details.section'])->findOrFail($id);
        
        $courses=Course::with('course_details')->whereHas('course_details',function($q){
            $q->whereUserId(auth()->user()->id);
        })->get();
        $sections=CourseDetail::with(['section'])->whereCourseId($meeting->course_id)->whereUserId(auth()->user()->id)->get();
        
        $meeting_sections=MeetingDetail::whereMeetingId($meeting->id)->pluck('section_id')->toArray();

        return view('teachers.meetings.edit',compact('meeting','courses','sections','meeting_sections'));


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
        $section_ids=$request->section_id;
        $meeting=Meeting::findOrFail($id);
        
        $meeting->update([
            'start_time'=>date('H:i:s',strtotime($request->start_time)),
            'end_time'=>date('H:i:s',strtotime($request->end_time)),
            'start_date'=>$request->start_date,
            'end_date'=>$request->end_date,
            'token'=>$request->password,
        ]);

        $old_sections=$meeting->meeting_details()->pluck('section_id')->toArray();
        $new_sections=array_filter($section_ids,function($data) use($old_sections){
            return !in_array($data,$old_sections) ;
        });
        $not_old_sections=array_filter($old_sections,function($data) use($section_ids){
            return !in_array($data,$section_ids) ;
        });

        
        MeetingDetail::whereIn('section_id',$not_old_sections)->delete();
        
        MeetingDetail::insert(array_map(function($data) use($meeting){
            return [
                'section_id'=>$data,
                'meeting_id'=>$meeting->id,
                'status'=>1,
            ];
        },$new_sections));
        return redirect('/meetings')->with('success','Data updated');
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
        $meeting=Meeting::findOrFail($id);
        $meeting->meeting_details()->delete();
        $meeting->delete();
        return redirect('/meetings')->with('success','Data deleted');
    }

    public function upload(Request $request, FileHelper $file)
    {
        if($request->video){
            $fileName ='meetings'.'-'. Str::uuid() . '.mp4';
            Storage::disk(config('app.storage_driver'))->putFileAs(
                'meetings', $request->video, $fileName
            );
            MeetingVideo::create(['meeting_id'=>$request->meeting_id,'video'=>$fileName]);
        }
        return response()->json(200);
    }
}
