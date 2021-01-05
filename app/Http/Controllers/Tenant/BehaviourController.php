<?php

namespace App\Http\Controllers\Tenant;

use App\Behaviour;
use App\Classroom;
use App\CourseDetail;
use App\StudentDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Section;
use App\User;
use App\UserSectionBehaviour;
use Arr;

class BehaviourController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $class_sections=Section::with(['classroom','course_details'])->whereHas('course_details',function($q){
            $q->whereUserId(auth()->user()->id);
        })->get();
        $is_classTeacher=Section::whereUserId(auth()->user()->id)->count()>0;
        return view('admin.behaviours.index', compact('class_sections','is_classTeacher'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sections = CourseDetail::where('user_id', auth()->user()->id)->where('status', 1)->groupBy('section_id')->pluck('section_id');
        // dump($sections);
        if ($sections) {
            $classrooms = Classroom::where('status', 1)->with([
                'sections' => function($q) use($sections) {
                    $q->whereIn('id', $sections);
                }
            ])
            ->get();
        }
        else {
            return back()->with('error', 'No classrooms assigned yet.');
        }

        // dd($classrooms->toArray());

        return view('admin.behaviours.create', compact('classrooms'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'behaviour' => 'required',
            'student_id' => 'required',
            'marks' => 'required|integer',
        ]);

        $request->merge(['teacher_id' => auth()->user()->id]);

        Behaviour::create($request->all());

        return redirect('/behaviours')->with('success', 'Data saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Behaviour  $behaviour
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //

        $class_sections=Section::with(['classroom','course_details'])->whereHas('course_details',function($q){
            $q->whereUserId(auth()->user()->id);
        })->get();
        $is_classTeacher=Section::whereUserId(auth()->user()->id)->count()>0;
        $section=$class_sections->where('id',$id)->first();
        $section_behaviours=$section->section_behaviours()->whereStatus(1)->with(['behaviour_type'])->get();
        $users=User::with(['student_detail','user_section_behaviours'])->whereHas('student_detail',function($q) use($section){
            $q->whereSectionId($section->id);
        })->get()
        ->map(function($data) use($section_behaviours){
            $arr=[];
            foreach($section_behaviours as $sb){
                $val="";
                foreach($data['user_section_behaviours'] as $ub){
                    if($sb->id==$ub->section_behaviour_id && $ub->teacher_id==auth()->user()->id){
                        $val=$ub->marks;
                    }
                }
               $arr=array_merge($arr,[$sb->behaviour_type->title=>$val]);
            }
            return 
                array_merge([
                    'id'=>$data->id,
                    'name'=>$data->name,
                ],$arr);
        });
        return view('admin.behaviours.show', compact('section','section_behaviours','users','class_sections','is_classTeacher'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Behaviour  $behaviour
     * @return \Illuminate\Http\Response
     */
    public function edit(Behaviour $behaviour)
    {
        return view('admin.behaviours.edit', compact('behaviour'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Behaviour  $behaviour
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Behaviour $behaviour)
    {
        $request->validate([
            'behaviour' => 'required',
            'marks' => 'required|integer',
        ]);

        $behaviour->update($request->all());

        return redirect('/behaviours')->with('success', 'Data updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Behaviour  $behaviour
     * @return \Illuminate\Http\Response
     */
    public function destroy(Behaviour $behaviour)
    {
        $behaviour->delete();

        return redirect('/behaviours')->with('success', 'Data deleted.');
    }

    public function guardianBehaviours()
    {
        $student = StudentDetail::where('guardian_id', auth()->user()->id)->first();
        $behaviour=UserSectionBehaviour::with(['section_behaviour.behaviour_type','student','teacher'])->whereUserId($student->user_id)->get();
        dd($behaviour);
        $behaviours = Behaviour::where('student_id', $student->user_id)
            ->with(['student.student_detail.section.classroom', 'student.student_detail.classroom', 'teacher'])
            ->get()
            ->map(function($data) {
                return [
                    'id' => $data->id,
                    'student' => $data->student->name,
                    'teacher' => $data->teacher->name,
                    'classroom' => $data->student->student_detail->classroom->title . ' (' . $data->student->student_detail->section->title . ')',
                    'behaviour' => $data->behaviour,
                    'marks' => $data->marks,
                ];
            });

        return view('admin.behaviours.guardian', compact('behaviours'));
    }

    public function updateMark(Request $request)
    {
        $userBehaviour=UserSectionBehaviour::whereUserId($request->user)->whereSectionBehaviourId($request->sb)->whereTeacherId(auth()->user()->id)->first();
        if($userBehaviour){
            $userBehaviour->update(['marks'=>$request->new_value]);
        }else{
            UserSectionBehaviour::create([
                'user_id'=>$request->user,
                'teacher_id'=>auth()->user()->id,
                'section_behaviour_id'=>$request->sb,
                'marks'=>$request->new_value,
                'status'=>1
            ]);
        }
        return response()->json(['data'=>'success']);
    }
}
