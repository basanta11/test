<?php

namespace App\Http\Controllers\Tenant\Teacher;

use App\CourseDetail;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Lesson;
use App\Test;
use App\TestQuestion;
use App\TestSet;
use App\User;
use DB;
use Exception;
use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Lesson $lesson)
    {
        //
        $sn=0;
        $tests = Test::with(['testSets','lesson'])->whereLessonId($lesson->id)->whereCreatedBy(auth()->user()->id)
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
                'test_start' => date('jS M, Y g:i a', strtotime($data->test_start)),
                'full_marks' => $data->full_marks,
                'type'=> $data->type==0 ? 'Pre Test' : 'Post Test',
                'pass_marks' => $data->pass_marks,
                'duration' => $data->duration.' minutes',
                'status' => $status,
                'sets'=>$data['testSets'],
                'lesson'=>$data['lesson']['title'],
                'result'=>$data->show_result,
                'created_at' => $data->created_at,
            ];
        });
        return view('teachers.tests.index',compact('tests','lesson'));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Lesson $lesson)
    {
        //
        return view('teachers.tests.create',compact('lesson'));
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
        // dd($request->all());
        $request->merge(['status' => 1,'created_by'=>auth()->user()->id]);
        $getSets = explode(",", $request->sets);
        foreach ($getSets as $key => $value) {
            $sets[$key]['title'] = $value;
        }

        DB::beginTransaction();
        try {
            $test = Test::create($request->only([
                'title','full_marks','pass_marks','duration','type' ,'test_start' ,'lesson_id','status','created_by'
            ]));
            $test->testSets()->createMany($sets);
            $lesson_id=$request->lesson_id;
            $section_id=CourseDetail::with(['course.lessons'])->whereHas('course.lessons',function($q)use($lesson_id){
                $q->whereId($lesson_id);
            })->pluck('section_id');
            $users_section=User::with('student_detail')->whereHas('student_detail',function($q) use($section_id){
                $q->whereIn('section_id',$section_id);
            })->get();
            if(tenant()->plan==='large'){
                $notify->notifyMany(
                    $users_section,
                    'App\Test',
                    $test->id,
                    '/tests-students/'.$test->id.'/view',
                    auth()->user()->name . ' has added a new test. Title: '.$test->title
                );
            }
            DB::commit();
        }
        catch (Exception $e) {
            DB::rollback();
            dd($e);
            return back()->with('error', $e);
        }

        return redirect('/tests/'.$request->lesson_id)->with('success', 'Data saved.');
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
        $test=Test::with(['lesson'])->findOrFail($id);
        if($test->created_by != auth()->user()->id){
            abort(401);
        }
        $lesson=$test->lesson()->with(['course'])->first();
        $sets=$test->testSets()->get();
        return view('teachers.tests.show',compact('test', 'lesson','sets'));
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
        $test=Test::findOrFail($id);
        if($test->created_by != auth()->user()->id){
            abort(401);
        }
        $sets=$test->testSets()->get();
        $lesson=$test->lesson()->with('course')->first();
        return view('teachers.tests.edit',compact('test','sets','lesson'));
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
        $test=Test::findOrFail($id);
        DB::beginTransaction();
        try {
            if ($request->oldset) {
                foreach ($request->oldset as $id => $title) {
                    TestSet::where('id', $id)->update(['title' => $title]);
                }
            }

            if ($request->sets) {
                foreach ($request->sets as $v) {
                    if (!empty($v['sets'])) {
                        TestSet::create(['title' => $v['sets'], 'test_id' => $test->id]);
                    }
                }
            }
            $test_sets=$test->testSets;
            foreach($test_sets as $ts){
                $pdf=$ts->testQuestions()->whereType(0)->first();
                if($pdf){
                    $pdf->update(['marks'=>$request->full_marks]);
                }
            }

            $test->update($request->only([
                'title','full_marks','pass_marks','duration','type' ,'test_start' ,'lesson_id'
            ]));

            DB::commit();
        }
        catch (Exception $e) {
            DB::rollback();
            
            return back()->with('error', $e);
        }

        return redirect('/tests/'.$test->lesson_id)->with('success', 'Data updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $test=Test::findOrFail($id);
        if($test->test_start < now()->addHours(3)){
            return back()->with('error','You cannot delete this test.');
        }
        $lesson_id=$test->lesson_id;
        $test->delete();
        return redirect('/tests/'.$lesson_id)->with('success','Successfully deleted');
        //
    }

    public function changeResult($id,$result)
    {
        $test=Test::findOrFail($id);
        $test->update(['show_result'=>$result]);
        return redirect('/tests/'.$test->lesson_id)->with('success','Successfully deleted');
        //
    }

    public function statusControl($id,$status)
    {
        if(!in_array($status,[1,0])){
            return back()->with('error','Action cannot be recognized. Please try again.');
        }

        $message = $status == 1 ? 'Test activated.': 'Test deactivated' ;
        $exam = Test::findOrFail($id);

        $exam->update(['status' => $status]);

        return back()->with('success', $message);
    }
}
