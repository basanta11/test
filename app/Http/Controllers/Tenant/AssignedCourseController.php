<?php

namespace App\Http\Controllers\Tenant;

use App\Course;
use App\Helpers\FileHelper;
use App\Lesson;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AssignedCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sn = 0;
    
        $courses = Course::with(['course_details.section'])->whereHas('course_details', function($q) {
                $q->where('user_id', auth()->user()->id);
            })->
            with(['classroom'])
            ->get()
            ->map(function($data, $sn) {

                $sections=isset($data['course_details']) ? $data['course_details']->where('user_id',auth()->user()->id)->map(function($q){
                    return $q->section;
                }) : null;
    
                
                $classroom=$data['classroom'] ? $data['classroom']['title'] : 'N/A';
                
                return [
                    'sn' => $sn += 1,
                    'id' => $data->id,
                    'title' => $data->title,
                    'credit_hours' => $data->credit_hours,
                    'learn_what' => $data->learn_what,
                    'description'=>$data->description,
                    'classroom'=>$classroom,
                    'sections'=>$sections,
                    'status' => $data->status == 1 ? 11 : 10,
                    'created_at' => $data->created_at,
                ];
            });

        return view('admin.assigned-courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show($id, FileHelper $file)
    {
        $sn = 0;
        $course = Course::with(['course_details.user','course_details','course_details.section'])->findOrFail($id);

        if ( !empty($course->course_details) && !in_array(auth()->user()->id, $course->course_details->pluck('user_id')->toArray()) ) {
            abort(401);
        }

        $lessons = Lesson::with(['topics'])->whereCourseId($course->id)->get()->map(function($data, $sn) {
            return [
                'sn' => $sn += 1,
                'id' => $data->id,
                'title' => $data->title,
                'brief' => $data->brief,
                'status' => $data->status == 1 ? 11 : 10,
                'created_at' => $data->created_at,
            ];
        });
        
        return view('admin.assigned-courses.show',compact('course', 'lessons','file'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        if ( !empty($course->course_details) && !in_array(auth()->user()->id, $course->course_details->pluck('user_id')->toArray()) ) {
            abort(401);
        }
        
        return view('admin.assigned-courses.edit', compact('course'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course)
    {
        request()->validate([
            'learn_what'=>'required|max:500',
        ]);

        $course->update($request->only(['learn_what']));
        
        return redirect('/assigned-courses')->with('success','Data updated.');
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
}
