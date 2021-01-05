<?php

namespace App\Http\Controllers\Tenant\Teacher;

use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Test;
use App\TestSetUser;
use App\TestAnswer;
use App\TestQuestion;
use App\TestSet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestSubmissionController extends Controller
{
    //
    public function index(Test $test)
    {
        $lesson=$test->lesson()->with('course')->first();
        $sets = $test->testSets->pluck('id')->toArray();
        $sets=TestSet::whereIn('id',$sets)->get();
        
        $setuser = TestSetUser::whereIn('test_set_id', $sets->pluck('id'))
        ->with('test_set', 'user:id,name', 'user.student_detail.classroom', 'user.student_detail.section')
        ->get()
        ->map(function($data) {
            $is_finished=10;

            if($data->is_finished==1) {
                $is_finished=11;
            }
            elseif($data->is_finished==0) {
                $is_finished=10;
            }

            $obtained_marks = TestAnswer::where('test_set_user_id', $data->id)->sum('marks');

            return [
                'id' => $data->id,
                'user_id' => $data->user_id,
                'set_id' => $data->test_set_id,
                'teacher_checking' => $data->teacher_checking,
                'is_finished' => $is_finished,
                'name' => $data->user->name,
                'set' => $data->test_set->title,
                'classroom' => $data->user->student_detail->classroom->title,
                'section' => $data->user->student_detail->section->title,
                'roll_number' => $data->user->student_detail->roll_number,
                'created_at' => date('jS M, Y g:i a', strtotime($data->updated_at)),
                'total' => $data->test_set->test->full_marks,
                'obtained_marks' => $obtained_marks,
            ];
        });
        return view('teachers.test-submissions.index', compact('sets', 'setuser','lesson','test'));
    }

    public function show($id)
    {
        $setuser=TestSetUser::findOrFail($id);
        $test=$setuser->test_set->test;
        if($test->created_by != auth()->user()->id){
            abort(401);
        }
        $lesson=$test->lesson()->with('course')->first();
        $formattedAnswers = [];
        $set = $setuser->test_set;
        $questions = TestQuestion::where('test_set_id', $set->id)->orderBy('order', 'asc')->with(['test_question_options'])->get();

        if ($questions->first()->type == 0) {
            $type = 'PDF';
        }
        else {
            $type = 'Created';
        }

        $answers = TestAnswer::where('test_set_user_id', $setuser->id)->get();
        foreach ($answers as $key => $value) {
            $formattedAnswers[$value->test_question_id]['question_option_id'] = $value->test_question_option_id;
            $formattedAnswers[$value->test_question_id]['answer'] = $value->answer;
            $formattedAnswers[$value->test_question_id]['marks'] = $value->marks;
        }

        return view('teachers.test-submissions.show', compact('questions', 'type', 'set', 'answers', 'setuser', 'formattedAnswers','lesson','test'));
    }
    public function edit($id)
    {
        $setuser=TestSetUser::findOrFail($id);
        $test=$setuser->test_set->test;
        if($test->created_by != auth()->user()->id){
            abort(401);
        }
        $lesson=$test->lesson()->with('course')->first();
        $formattedAnswers = [];
        $set = $setuser->test_set;
        $questions = TestQuestion::where('test_set_id', $set->id)->orderBy('order', 'asc')->with(['test_question_options'])->get();

        if ($questions->first()->type == 0) {
            $type = 'PDF';
        }
        else {
            $type = 'Created';
        }

        $answers = TestAnswer::where('test_set_user_id', $setuser->id)->get();
        foreach ($answers as $key => $value) {
            $formattedAnswers[$value->test_question_id]['question_option_id'] = $value->test_question_option_id;
            $formattedAnswers[$value->test_question_id]['answer'] = $value->answer;
            $formattedAnswers[$value->test_question_id]['marks'] = $value->marks;
        }

        return view('teachers.test-submissions.edit', compact('questions', 'type', 'set', 'answers', 'setuser', 'formattedAnswers','lesson','test'));
    }

    public function autoSave(Request $request)
    {
        $data = $request->all();

        foreach ($data as $key => $value) {
            if ((!empty($value) || $value != null) && strpos($key, 'question-') !== false) {
                $questionId = substr($key, 15);
                
                $setuser = TestSetUser::where('test_set_id', $data['set_id'])->where('user_id', $data['user_id'])->first();
                if ($setuser->teacher_checking == 1) {
                    $setuser->update(['teacher_checking' => 2]);
                }
                        
                $question = TestQuestion::where('id', $questionId)->first();

                TestAnswer::where('test_set_user_id', $setuser->id)->where('test_question_id', $question->id)->update(['marks' => $value]);
            }
        }

        return response()->json(['result' => 'success']);
    }

    public function finish(TestSetUser $testsetuser, Request $request, NotificationHelper $notify)
    {
        if($request->question_type == "upload-pdf") {
            TestAnswer::where('id', $request->answer_id)->update(['marks' => $request->marks]);
        }
        
        $testsetuser->update(['teacher_checking' => 3]);
        $test=$testsetuser->test_set->test;
        // if($test->status==1){
        //     if(tenant()->plan==='large'){
        //         $notify->notifyOne(
        //             $testsetuser->user,
        //             'App\TestSetUser',
        //             $testsetuser->id,'/tests-results/'.$testsetuser->id, 
        //             auth()->user()->name . ' has finished checking exam of '.$test->title
        //         );
        //     }
        // }
        
        return redirect('/tests/submissions/'.$testsetuser->test_set->test_id)->with('success', 'Data saved.');
    }

    public function downloadAnswer(TestAnswer $testanswer)
    {
        return Storage::disk(config('app.storage_driver'))->download('/answers/'.$testanswer->answer);
    }

    public function downloadQuestion(TestQuestion $testquestion)
    {
        return Storage::disk(config('app.storage_driver'))->download('/attachments/'.$testquestion->attachments->first()->body);
    }
}
