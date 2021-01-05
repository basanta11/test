<?php

namespace App\Http\Controllers\Tenant\Student;

use App\Test;
use App\User;
use DateTime;
use App\TestSet;
use App\TestAnswer;
use App\TestSetUser;
use App\CourseDetail;
use App\TestQuestion;
use App\Helpers\FileHelper;
use Illuminate\Http\Request;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class TestController extends Controller
{
    public function index($id)
    {
        if ( !in_array(auth()->user()->student_detail->section_id, CourseDetail::where('course_id', $id)->pluck('section_id')->toArray()) ) {
            abort(401);
        }

        $sn = 0;

        $section = auth()->user()->student_detail->section_id;

        $tests = Test::with('lesson.course')->whereHas('lesson.course',function($q) use($id){
            $q->whereId($id);
        })
        ->get()
        ->map(function($data, $sn) {
            $status=10;
            if($data->status == 1)
            {
                $status=11;
            }elseif($data->status == 2){
                $status=12;
            }

            if($data->type == 0) {
                $type = 'Pre Test';
            }
            else {
                $type = 'Post Test';
            }

            $setuser = TestSetUser::whereIn('test_set_id', $data->testSets->pluck('id'))->where('user_id', auth()->user()->id)->first();

            $is_finished = 10;
            if ($setuser) {
                if($setuser->is_finished == 0) {
                    $is_finished = 10;
                }
                elseif($setuser->is_finished == 1){
                    $is_finished = 11;
                }
            }
            else {
                $is_finished = 'no';
            }
           
            return [
                'sn' => $sn+=1,
                'id' => $data->id,
                'title' => $data->title,
                'test_start' => date('jS M, Y g:i a', strtotime($data->test_start)),
                'full_marks' => $data->full_marks,
                'lesson'=>$data['lesson']['title'],
                'pass_marks' => $data->pass_marks,
                'duration' => $data->duration.' minutes',
                'type' => $type,
                'is_finished' => $is_finished,
                'status' => $status,
                'created_at' => $data->created_at,
            ];
        });

        return view('students.tests.index', compact('tests','id'));
    }
    //
    public function show(Test $test)
    {
        if ( !in_array(auth()->user()->student_detail->section_id, $test->lesson->course->course_details->pluck('section_id')->toArray()) ) {
            abort(401);
        }

        $setuser = TestSetUser::whereIn('test_set_id', $test->testSets->pluck('id'))->where('user_id', auth()->user()->id)->first();
        
        $is_finished = 10;
        if ($setuser) {
            if($setuser->is_finished == 0) {
                $is_finished = 10;
            }
            elseif($setuser->is_finished == 1){
                $is_finished = 11;
            }
        }
        else {
            $is_finished = 'no';
        }
        $currenttime = $current = new DateTime($test->test_start);
        $minutes = (int) $test->duration;
        $endtime = $currenttime->modify('+'.$minutes.' minutes')->format('Y-m-d H:i:s');

        $id=$test->lesson->course->id;
        return view('students.tests.show', compact('test', 'endtime','id','is_finished'));
    }


    public function start(Test $test)
    {
        $currenttime = $current = new DateTime($test->test_start);
        $minutes = (int) $test->duration;
        $endtime = $currenttime->modify('+'.$minutes.' minutes')->format('Y-m-d H:i:s');

        if ( date('Y-m-d H:i:s') > $endtime || date('Y-m-d H:i:s') < $test->test_start ) {
            abort(403, 'You cannot start this Test now.');
        }

        $test_sets = $test->testSets->pluck('id');
        $answers = collect();

        if ( TestSetUser::where('user_id', auth()->user()->id)->whereIn('test_set_id', $test_sets)->exists() ) {
            $setuser = TestSetUser::where('user_id', auth()->user()->id)->whereIn('test_set_id', $test_sets)->first();
            $set = TestSet::where('id', $setuser->test_set_id)->first();

            $answers = TestAnswer::where('test_set_user_id', $setuser->id)->get();
        }
        else {
            $set = $test->testSets->random();
            TestSetUser::create([
                'test_set_id' => $set->id,
                'user_id' => auth()->user()->id
            ]);
        }
        $questions = TestQuestion::where('test_set_id', $set->id)->orderBy('order', 'asc')->with(['test_question_options'])->get();


        if ($questions->isEmpty()) {
            return back()->with('error', 'This question paper does not have any questions!');
        }

        if ($questions->first()->type == 0) {
            $type = 'PDF';
        }
        else {
            $type = 'Created';
        }

        $id=$test->lesson->course->id;
        return view('students.tests.start', compact('test', 'questions', 'type', 'set', 'answers', 'endtime','id'));
    }

    public function downloadQuestion(TestQuestion $testquestion, User $user)
    {
        if ( !TestSetUser::where('test_set_id', $testquestion->test_set_id)->where('user_id', $user->id)->exists() && in_array([auth()->user()->role_id],[1,2,3])) {
            return back()->with('error', 'You do not have permission to download this attachment.');
        }

        return Storage::disk(config('app.storage_driver'))->download('/attachments/'.$testquestion->attachments->first()->body);
    }

    public function finish(Request $request, FileHelper $file, NotificationHelper $notify)
    {
        if ( !TestSetUser::where('test_set_id', $request->test_set_id)->where('user_id', auth()->user()->id)->exists() ) {
            return back()->with('error', 'Something went wrong.');
        }

        $setuser = TestSetUser::with(['test_set.test.lesson.course'])->where('test_set_id', $request->test_set_id)->where('user_id', auth()->user()->id)->first();
      
        if ($request->question_type == "upload-pdf") {
            if ($request->upload_answer) {
                $filename = $file->storeFile($request->upload_answer, 'answers');
            }
            else {
                $filename = null;
            }

            TestAnswer::create([
                'test_set_user_id' => $setuser->id,
                'test_question_id' => $request->question_id,
                'answer' => $filename
            ]);
        }
        $course_id=$setuser['test_set']['test']['lesson']['course_id'];
        $setuser->update(['is_finished' => 1]);
        if(tenant()->plan==='large'){
            $notify->notifyOne($setuser->test_set->test->teacher, 'App\TestSetUser',$setuser->id,'/tests/submissions/'.$setuser->id.'/show', auth()->user()->name. ' has submitted test. Title: '.$setuser['test_set']['test']['title']);
        }
        return redirect('/tests-students/'.$course_id)->with('success', 'Test submission successful.');
    }

    public function autoSave(Request $request)
    {
        if ( !TestSetUser::where('test_set_id', $request->test_set_id)->where('user_id', auth()->user()->id)->exists() ) {
            return back()->with('error', 'Something went wrong.');
        }

        $data = $request->all();

        foreach ($data as $key => $value) {
            if ((!empty($value) || $value != null) && strpos($key, 'question-') !== false) {
                $questionId = substr($key, 9);
                
                $setuser = TestSetUser::where('test_set_id', $data['test_set_id'])->where('user_id', auth()->user()->id)->first();
                $question = TestQuestion::where('id', $questionId)->first();

                if ( TestAnswer::where('test_question_id', $questionId)->where('test_set_user_id', $setuser->id)->exists() ) {
                    $answer = TestAnswer::where('test_question_id', $questionId)->where('test_set_user_id', $setuser->id)->first();

                    if ($question->type == 1) {
                        $answer->update([
                            'test_set_user_id' => $setuser->id,
                            'test_question_id' => $questionId,
                            'test_question_option_id' => $value
                        ]);
                    }
                    else if ($question->type == 2) {
                        $answer = TestAnswer::where('test_question_id', $questionId)->where('test_set_user_id', $setuser->id)->delete();
                        
                        foreach ($value as $v) {
                            $answer = TestAnswer::create([
                                'test_set_user_id' => $setuser->id,
                                'test_question_id' => $questionId,
                                'test_question_option_id' => $v
                            ]);
                        }
                    }
                    else {
                        $answer->update([
                            'test_set_user_id' => $setuser->id,
                            'test_question_id' => $questionId,
                            'answer' => $value
                        ]);
                    }
                }
                else {
                    if ($question->type == 1) {
                        $answer = TestAnswer::create([
                            'test_set_user_id' => $setuser->id,
                            'test_question_id' => $questionId,
                            'test_question_option_id' => $value
                        ]);
                    }
                    else if ($question->type == 2) {
                        foreach ($value as $v) {
                            $answer = TestAnswer::create([
                                'test_set_user_id' => $setuser->id,
                                'test_question_id' => $questionId,
                                'test_question_option_id' => $v
                            ]);
                        }
                    }
                    else {
                        $answer = TestAnswer::create([
                            'test_set_user_id' => $setuser->id,
                            'test_question_id' => $questionId,
                            'answer' => $value
                        ]);
                    }
                }
            }
        }

        return response()->json(['result' => $request->all()]);
    }

}
