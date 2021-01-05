<?php

namespace App\Http\Controllers\Tenant;

use App\Classroom;
use App\Course;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Schedule;
use App\Section;
use App\User;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $sn=0;
        $schedules=Schedule::with(['section','section.classroom'])->get()
        ->unique('section_id')->values()->map(function($q,$sn){
            return [
                'sn'=>$sn+=1,
                'id'=>$q->id,
                'classroom'=>$q['section']['classroom']['title'],
                'status'=>$q->status+=10,
                'type'=>$q->type==0 ? 'Break' : 'Class',
                'section'=>$q['section']['title'],
                'section_id'=>$q['section']['id'],
            ];
        });
       
        return view('admin.schedules.index',compact('schedules'));
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
        return view('admin.schedules.create',compact('classrooms'));
    }

    public function createSection($id)
    {
        //
        $section=Section::with(['classroom','course_details'])->findOrFail($id);
        $courses=$section['course_details']->map(function($q){
            return $q['course'];
        });
        $days=$this->days();
        return view('admin.schedules.create-section',compact('section','courses','days','id'));
    }

    
    public function getSections($id)
    {
        $classroom=Classroom::with(['sections'])->whereId($id)->first();
        if($classroom)
            return response()->json(['status'=>true,'data'=>$classroom['sections']]);
        else
            return response()->json(['status'=>false]);
    }

    public function getCourses($id)
    {
        $section=Section::with(['course_details.course'])->whereId($id)->first();
        if($section){
            $courses=$section['course_details']->map(function($q){
                return $q['course'];
            });
            return response()->json(['status'=>true,'data'=>$courses]);
        }
        else
            return response()->json(['status'=>false]);
    }

    public function days()
    {
        return ['sunday','monday','tuesday','wednesday','thursday','friday','saturday'];
    }

    public function validateTime($start,$end,$day,$section)
    {
        $start=strtotime($start);

        $end=strtotime($end);
        if(!request()->id){
            $schedules=Schedule::where('day',$day)->whereSectionId($section)->get();
        }else{
            $schedules=Schedule::where('day',$day)->where('id','<>',request()->id)->whereSectionId($section)->get();
        }
        foreach($schedules as $s)
        {
            if( 
                ($start>strtotime($s->start_time) && $start<strtotime($s->end_time)) 
                || ($end>strtotime($s->start_time) && $end<strtotime($s->end_time))
                || (strtotime($s->start_time)>$start && strtotime($s->start_time)<$end)
                || (strtotime($s->end_time)>$start && strtotime($s->end_time)<$end)
                || ($start==strtotime($s->start_time)  && $end==strtotime($s->end_time))
            )
            {
                return response()->json(['status'=>false,'message'=>'Time already exist for '.$day]);
            }
        }
        return response()->json(['status'=>true]);
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
        if(!in_array($request->day,$this->days())){
            return back()->with('error','There was a problem proceeding. Please try again.');
        }
        request()->validate([
            'section_id'=>'required',
            'day'=>'required',
            'start_time'=>'required',
            'end_time'=>'required',
        ]);
        $arr=array_merge(
            $request->only(['section_id', 'day', 'start_time', 'end_time']),
            [
                'status'=>1,
                'start_time'=>date('H:i:s',strtotime($request->start_time)),
                'end_time'=>date('H:i:s',strtotime($request->end_time))
            ]
        );
        if(isset($request->type))
        {
            $arr=array_merge($arr,['type'=>1,'course_id'=>$request->course_id]);
        }else{
            $arr=array_merge($arr,['type'=>0]);
        }
        $section=Section::findOrFail($request->section_id);
        $schedule=Schedule::create($arr);
        $users_section=User::with('student_detail')->whereHas('student_detail',function($q) use($section){
            $q->where('section_id',$section->id);
        })->get();
        $teachers_section=User::with(['course_details'])->whereHas('course_details',function($q) use($section){
            $q->where('section_id',$section->id);
        })->get();
        if(tenant()->plan==='large'){
            $notify->notifyMany(
                $teachers_section, 
                'App\Schedule', 
                $schedule->id,
                '/my-schedule',
                auth()->user()->name . ' has added a new schedule. Title: '.$schedule->title
            );
            $notify->notifyMany(
                $users_section,
                'App\Schedule',
                $schedule->id,
                '/my-schedule',
                auth()->user()->name . ' has added a new schedule. Title: '.$schedule->title
            );
        }
        return redirect('/schedules')->with('success','Data saved.');
    }

    public function storeSection(Request $request,$id)
    {
        //
        if(!in_array($request->day,$this->days())){
            return back()->with('error','There was a problem proceeding. Please try again.');
        }
        request()->validate([
            'day'=>'required',
            'start_time'=>'required',
            'end_time'=>'required',
        ]);
        $arr=array_merge(
            $request->only(['section_id', 'day']),
            [
                'status'=>1,
                'start_time'=>date('H:i:s',strtotime($request->start_time)),
                'end_time'=>date('H:i:s',strtotime($request->end_time)),
                'section_id'=>$id,
                'classroom_id'=>Section::findOrFail($id)->classroom_id,
            ]
        );
        if(isset($request->type))
        {
            $arr=array_merge($arr,['type'=>1,'course_id'=>$request->course_id]);
        }else{
            $arr=array_merge($arr,['type'=>0]);
        }
        Schedule::create($arr);
        return redirect('/schedules/'.$id)->with('success','Data saved.');
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
        $days=$this->days();
        $schedule=Schedule::with(['section','section.classroom','section.course_details.user','course'])
        ->whereSectionId($id)->orderBy('start_time')->get()
        ->groupBy('day');
        $section=Section::with(['classroom'])->findOrFail($id);
        $today=now()->englishDayOfWeek;
        return view('admin.schedules.show',compact('schedule','days','section','today'));
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
        $schedule=Schedule::with(['section','section.course_details','section.classroom','course'])->findOrFail($id);
        $courses=$schedule['section']['course_details']->map(function($q){
            return $q['course'];
        });
        $days=$this->days();
        return view('admin.schedules.edit',compact('schedule','courses','days'));
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

        $schedule=Schedule::with(['section'])->findOrFail($id);
        if(!in_array($request->day,$this->days())){
            return back()->with('error','There was a problem proceeding. Please try again.');
        }
        request()->validate([
            'day'=>'required',
            'start_time'=>'required',
            'end_time'=>'required',
        ]);
        $arr=array_merge(
            $request->only(['day']),
            [
                'start_time'=>date('H:i:s',strtotime($request->start_time)),
                'end_time'=>date('H:i:s',strtotime($request->end_time))
            ]
        );
        if(isset($request->type))
        {
            $arr=array_merge($arr,['type'=>1,'course_id'=>$request->course_id]);
        }else{
            $arr=array_merge($arr,['type'=>0,'course_id'=>null]);
        }
        $schedule->update($arr);
        return redirect('/schedules/'.$schedule['section']['id'])->with('success','Data updated.');

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
        Schedule::whereId($id)->delete();
       
        return back()->with('success','Data deleted.');
    }

    public function destroySection($id)
    {
        //
        Schedule::where('section_id',$id)->delete();
       
        return back()->with('success','Data deleted.');
    }

    // course
    // public function indexCourse()
    // {
    //     //
    //     $course=Course::with(['course_details'])->whereHas('course_details',function($q){
    //         $q->where('user_id',auth()->user()->id);
    //     })->pluck('id');
    //     $sn=0;
    //     $schedules=Schedule::with(['section','section.classroom','course'])->whereIn('course_id',$course)->get()
    //     ->unique('course_id')->values()->map(function($q,$sn){
    //         return [
    //             'sn'=>$sn+=1,
    //             'id'=>$q->id,
    //             'classroom'=>$q['section']['classroom']['title'],
    //             'status'=>$q->status+=10,
    //             'type'=>$q->type==0 ? 'Break' : 'Class',
    //             'section'=>$q['section']['title'],
    //             'section_id'=>$q['section']['id'],
    //             'course'=>$q['course']['title'],
    //             'course_id'=>$q['course']['id'],
    //         ];
    //     });
    //     // dd($schedules);
       
    //     return view('admin.schedules.course-index',compact('schedules'));
    // }

    // public function showCourse($id)
    // {
    //     //
    //     $days=$this->days();
    //     $schedule=Schedule::with(['section','section.classroom','section.course_details.user','course'])
    //     ->whereCourseId($id)->orderBy('start_time')->get()
    //     ->groupBy('day');
    //     $section=Section::with(['classroom'])->findOrFail($id);

    //     $course=Course::with(['course_details'])->findOrFail($id);
        
    //     return view('admin.schedules.course-show',compact('schedule','days','section','course'));
    // }
}
