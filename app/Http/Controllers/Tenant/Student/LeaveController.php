<?php

namespace App\Http\Controllers\Tenant\Student;

use App\Application;
use App\Helpers\NotificationHelper;
use App\StudentDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LeaveController extends Controller
{
    public function index()
    {
        if ( auth()->user()->hasRole('Guardian') ) {
            $student = StudentDetail::where('guardian_id', auth()->user()->id)->first();

            $applications = Application::where('user_id', $student->user_id)
                ->orderby('created_at','desc')
                ->get()
                ->map(function($app) {
                    if ($app->status == 0) {
                        $status = 10;
                    }
                    else if ($app->status == 1) {
                        $status = 11;
                    }
                    else {
                        $status = 12;
                    }
    
                    return [
                        'id' => $app->id,
                        'body' => $app->body,
                        'date' => $app->leave_date,
                        'note' => $app->note ? $app->note : 'N/A',
                        'status' => $status,
                    ];
                });
        }
        else {
            $applications = Application::where('user_id', auth()->user()->id)
                ->orderby('created_at','desc')
                ->get()
                ->map(function($app) {
                    if ($app->status == 0) {
                        $status = 10;
                    }
                    else if ($app->status == 1) {
                        $status = 11;
                    }
                    else {
                        $status = 12;
                    }
    
                    return [
                        'id' => $app->id,
                        'body' => $app->body,
                        'date' => $app->leave_date,
                        'note' => $app->note ? $app->note : 'N/A',
                        'status' => $status,
                    ];
                });
        }

        return view('students.applications.index', compact('applications'));
    }

    public function create()
    {
        return view('students.applications.create');
    }

    public function store(Request $request, NotificationHelper $notify)
    {
        $request->merge(['user_id' => auth()->user()->id, 'section_id' => auth()->user()->student_detail->section_id]);
        $application=Application::create($request->all());
        if(tenant()->plan==='large'){
            if(auth()->user()->student_detail->section->class_teacher){
                $notify->notifyOne(
                    auth()->user()->student_detail->section->class_teacher,
                    'App\Application',
                    $application->id,
                    '/applications',
                    auth()->user()->name . ' has sent a leave request.'
                );
            }
        }
        return redirect('/leave-applications')->with('success', 'Data saved.');
    }
}
