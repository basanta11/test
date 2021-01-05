<?php

namespace App\Http\Controllers\Tenant;

use App\User;
use Exception;
use App\Course;
use App\Section;
use App\Classroom;
use App\CourseDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sn = 0;
        $classrooms = Classroom::with('sections')->get()->map(function($data,$sn) {
            return [
                'sn' => $sn += 1,
                'id' => $data->id,
                'title' => $data->title,
                'description' => $data->description,
                'status' => $data->status == 1 ? 11 : 10,
                'sections' => $data->sections->count(),
            ];
        });

        return view('admin.classrooms.index', compact('classrooms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.classrooms.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'title' => ['required', 'string', 'max:255'],
            'sections' => ['required', 'string'],
        ]);

        $sections = explode(",", $request->sections);

        foreach ($sections as $key => $value) {
            $formatSections[$key]['title'] = $value;
            $formatSections[$key]['status'] = 1;
        }
        
        DB::beginTransaction();
        try {
            $classroom = Classroom::create([
                'title' => $request->title,
                'description' => $request->description,
                'status' => 1
            ]);
            
            $classroom->sections()->createMany($formatSections);

            DB::commit();
        }
        catch (Exception $e) {
            DB::rollback();
            
            return back()->with('error', $e);
        }

        return redirect('/classrooms')->with('success', 'Data saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Classroom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function show(Classroom $classroom)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Classroom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function edit(Classroom $classroom)
    {
        $sections = $classroom->sections->pluck('title')->toArray();

        return view('admin.classrooms.edit', compact('classroom', 'sections'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Classroom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Classroom $classroom)
    {
        request()->validate([
            'title' => ['required', 'string', 'max:255']
        ]);

        $classroom->update([
            'title' => $request->title,
            'description' => $request->description
        ]);

        return redirect('/classrooms')->with('success', 'Data saved.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Classroom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function destroy(Classroom $classroom)
    {
        //
    }

    // $status -> send 1 to active, 2 to deactivate
    public function statusControl($id,$status)
    {
        if(!in_array($status,[0,1])){
            return back()->with('error','Action cannot be recognized. Please try again.');
        }
        $message=$status==1 ? 'Classroom activated.': 'Classroom deactivated' ;
        $classroom=Classroom::findOrFail($id);

        $classroom->update(['status'=>$status]);
        $classroom->sections()->update(['status' => $status]);

        return back()->with('success',$message);
    }

    // $status -> send 1 to active, 2 to deactivate
    public function statusControlBulk(Request $request,$status)
    {
        if(!in_array($status,[0,1]) || !json_decode($request->list)){
            return back()->with('error','Action cannot be recognized. Please try again.');
        }

        $message=$status==1 ? 'Classroom(s) activated.': 'Classroom(s) deactivated' ;
        $classroom = Classroom::whereIn('id',json_decode($request->list))->update(['status'=>$status]);
        Section::whereIn('classroom_id',json_decode($request->list))->update(['status' => $status]);

        return back()->with('success',$message);
    }

    public function getSection($id)
    {
        $sections = Classroom::findOrFail($id)->sections()->get();
        // dump($sections);
        return response()->json($sections);

    }

    public function getSectionsAndCourses(Classroom $classroom)
    {
        if ( !Course::where('classroom_id', $classroom->id)->exists() ) {
            return response()->json([ 'data' => null ]);
        }

        $data['sections'] = $classroom->sections;
        
        if ( CourseDetail::whereIn('section_id', $classroom->sections->pluck('id')->toArray())->exists() ) {
            $data['courses'] = CourseDetail::whereIn('section_id', $classroom->sections->pluck('id')->toArray())->select('id', 'course_id')->with(['course:id,title'])->groupBy('course_id')->get();
        }
        else {
            $data['courses'] = null;
        }

        return response()->json([ 'data' => $data ]);
    }

    public function assignClassTeacherPage(Classroom $classroom)
    {
        $sections = $classroom->sections;
        $coursedetails = CourseDetail::whereIn('section_id', $sections->pluck('id'))->groupBy('user_id')->get();
        
        if (!$coursedetails->isEmpty()) {
            $teachers = User::whereIn('id', $coursedetails->pluck('user_id'))->select('id', 'name')->get();
        }
        else {
            return back()->with('error', 'No teachers assigned to the courses of this classroom yet.');
        }

        return view('admin.classrooms.assign', compact('classroom', 'sections', 'teachers'));
    }

    public function assignClassTeacher(Classroom $classroom, Request $request)
    {
        if (!empty($request->teacher)) {
            foreach ($request->teacher as $key => $value) {
                // dd($value);
                Section::find($key)->update(['user_id' => $value['user_id']]);
            }
        }

        return redirect('/classrooms')->with('success', 'Data saved.');
    }
}
