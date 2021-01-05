<?php

namespace App\Http\Controllers\Tenant;

use App\Course;
use App\Http\Controllers\Controller;
use App\Schedule;
use Illuminate\Http\Request;

class AssignedScheduleController extends Controller
{
    //
    public function days()
    {
        return ['sunday','monday','tuesday','wednesday','thursday','friday','saturday'];
    }

    public function index()
    {
        $days=$this->days();
        if(auth()->user()->role_id==3){
            $schedule=Schedule::with(['section','section.classroom','section.course_details.user','course'])
            ->whereNotNull('course_id')
            ->whereHas('section.course_details',function($q){
                $q->where('user_id',auth()->user()->id);
            })->orderBy('start_time')->get()
            ->groupBy('day');
        }else{

            $sectionId = auth()->user()->student_detail->section_id;
            $schedule=Schedule::with(['section','section.classroom','section.course_details.user','course'])
            ->whereHas('section.course_details',function($q) use($sectionId){
                $q->where('section_id',$sectionId);
            })->orderBy('start_time')->get()
            ->groupBy('day');
        }
        $today=now()->englishDayOfWeek;
        // dd($schedule);
        return view('admin.schedules.my-index',compact('schedule','days','today'));
    }
}
