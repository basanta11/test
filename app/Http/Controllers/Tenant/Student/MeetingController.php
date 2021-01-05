<?php

namespace App\Http\Controllers\Tenant\Student;

use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Meeting;
use App\StudentDetail;
use Arr;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MeetingController extends Controller
{
    //
    public function index()
    {
        //
        $student_sections=StudentDetail::whereUserId(auth()->user()->id)->pluck('section_id')->toArray();
        $meetings=Meeting::with('course','course.classroom','meeting_details')
        ->whereHas('meeting_details',function($q) use($student_sections){
            $q->whereIn('section_id',$student_sections);
        })
        ->get()
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
                'classroom'=>$data['course']['classroom']['title']
            ];
        });
        return view('students.meetings.index',compact('meetings'));
    }

    public function show($id,FileHelper $file)
    {
        //
        $student_sections=StudentDetail::whereUserId(auth()->user()->id)->pluck('section_id')->toArray();

        $meeting=Meeting::findOrFail($id);
        if(empty(array_intersect($student_sections, $meeting->meeting_details()->pluck('section_id')->toArray())))
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
        return view('students.meetings.show',compact('meeting','jitsi_data'));
    }


}
