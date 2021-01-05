<?php

namespace App\Http\Controllers\Tenant;

use App\Section;
use App\Application;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LeaveController extends Controller
{
    public function index() 
    {
        if (auth()->user()->hasRole('Teacher')) {
            $my_sections = Section::where('user_id', auth()->user()->id)->get();

            $applications = Application::whereIn('section_id', $my_sections->pluck('id'))
                ->with(['user', 'section'])
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
                        'student' => $app->user->name,
                        'section' => $app->section->title,
                        'student_id' => $app->user_id,
                        'section_id' => $app->section_id,
                        'body' => $app->body,
                        'note' => $app->note ? $app->note : 'N/A',
                        'status' => $status,
                    ];
                });
        }
        else {
            $applications = Application::with(['user', 'section'])
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
                        'student' => $app->user->name,
                        'section' => $app->section->title,
                        'student_id' => $app->user_id,
                        'section_id' => $app->section_id,
                        'body' => $app->body,
                        'note' => $app->note ? $app->note : 'N/A',
                        'status' => $status,
                    ];
                });
        }

        return view('admin.applications.index', compact('applications'));
    }

    public function statusChange(Application $application, Request $request, NotificationHelper $notify)
    {
        $application->update(['status' => $request->status, 'note' => $request->note]);
        $notification='';
        if($request->status==1){
            $notification=auth()->user()->name . ' has approved your leave application.';

        }else{
            $notification=auth()->user()->name . ' has rejected your leave application.';
        }
        if(tenant()->plan==='large'){
            $notify->notifyOne(
                $application->user,
                'App\Application',
                $application->id,
                '/leave-applications',
                $notification
                
            );
        }
        return redirect('/applications')->with('success', 'Data updated.');
    }
}
