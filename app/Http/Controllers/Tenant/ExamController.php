<?php

namespace App\Http\Controllers\Tenant;

use App\Set;
use App\Exam;
use App\User;
use App\SetUser;
use Exception;
use App\Course;
use App\Classroom;
use App\CourseDetail;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Arr;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sn=0;
        $exams = Exam::with(['sections', 'sections.classroom'])
        ->get()
        ->map(function($data, $sn) {
            $status=10;
            if($data->status==1)
            {
                $status=11;
            }elseif($data->status==2){
                $status=12;
            }

            return [
                'sn' => $sn+=1,
                'id' => $data->id,
                'title' => $data->title,
                'course' => '<a href="/courses/'.$data->course_id.'">'.$data->course->title.'</a>',
                'teacher' => '<a href="/teachers/'.$data->user_id.'">'.$data->user->name.'</a>',
                'exam_start' => date('jS M, Y g:i a', strtotime($data->exam_start)),
                'full_marks' => $data->full_marks,
                'pass_marks' => $data->pass_marks,
                'duration' => $data->duration.' minutes',
                'sections' => $data->sections,
                'result'=>$data->show_result,
                'classroom' => $data->sections->first()->classroom,
                'status' => $status,
                'created_at' => $data->created_at,
            ];
        });

        return view('admin.exams.index', compact('exams'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $classrooms = Classroom::whereStatus(1)->get();

        return view('admin.exams.create', compact('classrooms'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, NotificationHelper $notify)
    {
        $request->merge(['status' => 1]);
        $getSets = explode(",", $request->sets);
        foreach ($getSets as $key => $value) {
            $sets[$key]['title'] = $value;
        }

        request()->validate([
            'title' => ['required'],
            'user_id' => ['required'],
            'course_id' => ['required'],
            'exam_start' => ['required'],
            'duration' => ['required'],
            'full_marks' => ['required'],
            'pass_marks' => ['required'],
        ]);

        DB::beginTransaction();
        try {
            $exam = Exam::create($request->all());
            $exam->sections()->attach($request->sections);
            $exam->sets()->createMany($sets);

            DB::commit();
            $section_id=$request->sections;
            $users_section=User::with('student_detail')->whereHas('student_detail',function($q) use($section_id){
                $q->whereIn('section_id',$section_id);
            })->get();
            if(tenant()->plan==='large'){
                $notify->notifyOne(User::findOrFail($request->user_id),'App\Exam',$exam->id,'/exam-teachers/'.$exam->id, auth()->user()->name. ' has added a new exam for your course');
                
                $notify->notifyMany(
                    $users_section,
                    'App\Exam',
                    $exam->id,
                    '/exam-students/'.$exam->id,
                    auth()->user()->name . ' has added a new exam. Title: '.$exam->title
                );
            }
        }
        catch (Exception $e) {
            DB::rollback();
            
            return back()->with('error', $e);
        }

        return redirect('/exams')->with('success', 'Data saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function show(Exam $exam)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function edit(Exam $exam)
    {
        $classrooms = Classroom::whereStatus(1)->get();
        $selectedSections = $exam->sections->pluck('id')->toArray();
        $selectedClass = $exam->sections->first()->classroom_id;
        $sections = Classroom::find($selectedClass)->sections;
        $courses = CourseDetail::whereIn('section_id', $selectedSections)->with(['course:id,title'])->groupBy('course_id')->get();
        $courseTeachers = Course::where('id', $exam->course_id)->first()->course_details->pluck('user_id');
        $teachers = User::whereIn('id', $courseTeachers)->select('id', 'name')->get();
        $sets = $exam->sets;

        return view('admin.exams.edit', compact('exam', 'classrooms', 'sections', 'selectedSections', 'selectedClass', 'courses', 'sets', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Exam $exam)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            if ($request->oldset) {
                foreach ($request->oldset as $id => $title) {
                    Set::where('id', $id)->update(['title' => $title]);
                }
            }

            if ($request->sets) {
                foreach ($request->sets as $v) {
                    if (!empty($v['sets'])) {
                        Set::create(['title' => $v['sets'], 'exam_id' => $exam->id]);
                    }
                }
            }

            request()->validate([
                'title' => ['required'],
                'user_id' => ['required'],
                'course_id' => ['required'],
                'exam_start' => ['required'],
                'duration' => ['required'],
                'full_marks' => ['required'],
                'pass_marks' => ['required']
            ]) ;
            $sets=$exam->sets;
            foreach($sets as $s){
                $pdf=$s->questions()->whereType(0)->first();
                if($pdf){
                    $pdf->update(['marks'=>$request->full_marks]);
                }
            }
            $exam->update([
                'title' => $request->title,
                'user_id' => $request->user_id,
                'course_id' => $request->course_id,
                'exam_start' => $request->exam_start,
                'duration' => $request->duration,
                'type' => $request->type,
                'full_marks' => $request->full_marks,
                'pass_marks' => $request->pass_marks
            ]);
            $exam->sections()->sync($request->sections);

            DB::commit();
        }
        catch (Exception $e) {
            DB::rollback();
            
            return back()->with('error', $e);
        }

        return redirect('/exams')->with('success', 'Data updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function destroy(Exam $exam)
    {
        //
    }

    // $status -> send 1 to active, 2 to deactivate
    public function statusControl($id,$status)
    {
        if(!in_array($status,[1,0])){
            return back()->with('error','Action cannot be recognized. Please try again.');
        }

        $message = $status == 1 ? 'Exam activated.': 'Exam deactivated' ;
        $exam = Exam::findOrFail($id);

        $exam->update(['status' => $status]);

        return back()->with('success', $message);
    }

    public function changeResult($id,$result, NotificationHelper $notify)
    {
        $exam=Exam::findOrFail($id);

        $dif = strtotime(now()->toDateTimeString()) - strtotime($exam->exam_start);
        if($dif<0){
            return redirect('/exams')->with('error','Exam has not been finished.');
        }

        // first logic
        $total_students=($exam->sections()->get()->map(function($q){
            return ($q->student_details()->get()->map(function($q2){
                return $q2->user()->whereStatus(1)->get();
            })->collapse());
        }))->collapse(); 
        // $attempStudents=array_sum($exam->sets()->get()->map(function ($q){
        //     $q->set_users()->get()->count();
        // })->toArray());
        
        // second logic
        $exam_set=$exam->sets->pluck('id')->toArray();
        $students=SetUser::with(['user'])->wherein('set_id',$exam_set)->where('teacher_checking',3)->get();

        if($result==1){
            if($students->count()!=0){
                $checked=$students->pluck('user_id')->toArray();
                foreach($total_students as $s){
                    if(in_array($s->id,$checked)){
                        // $set_user=$students->where("user_id",4)->first();
                        $notify->notifyOne($s,'App\SetUser',$s->id,'/results/'.$s->id,'The result of '. $exam->title.', Terminal : '.($exam->type + 1) . ', has been published.');
                    }
                }
    
            }
            else{
                return redirect('/exams')->with('error','Teacher has not finished checking the exam.');
            }
        }
        $exam->update(['show_result'=>$result]);
        return redirect('/exams')->with('success','Successfully changed');
    }
}
