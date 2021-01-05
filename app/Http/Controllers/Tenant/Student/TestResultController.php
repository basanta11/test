<?php

namespace App\Http\Controllers\Tenant\Student;

use App\Http\Controllers\Controller;
use App\TestAnswer;
use App\TestQuestion;
use App\TestSetUser;
use Illuminate\Http\Request;

class TestResultController extends Controller
{
    //
    public function index($id)
    {
        //
        $results = TestSetUser::where('user_id', auth()->user()->id)->whereTeacherChecking(3)
        ->with('test_set', 'test_set.test','test_set.test.lesson.course')
        ->whereHas('test_set.test',function($q){
            $q->whereShowResult(1);
        })
        ->whereHas('test_set.test.lesson.course',function($q)use($id){
            $q->whereId($id);
        })
        ->get()
        ->map(function($data) {
            $is_finished=10;

            if($data->is_finished==1) {
                $is_finished=11;
            }
            elseif($data->is_finished==0) {
                $is_finished=10;
            }

            $testType="Pre Test";
            if($data['test_set']['test']['type']==1){
                $testType="Post Test";
            }

            $obtained_marks = TestAnswer::where('test_set_user_id', $data->id)->sum('marks');

            return [
                'id' => $data->id,
                'user_id' => $data->user_id,
                'set_id' => $data->test_set_id,
                'exam_id'=>$data['test_set']['test']['id'],
                'exam_title'=>$data['test_set']['test']['title'],
                'exam_type'=>$testType,
                'course_id'=>$data['test_set']['test']['lesson']['course']['id'],
                'course_title'=>$data['test_set']['test']['lesson']['course']['title'],
                'teacher_checking' => $data->teacher_checking,
                'is_finished' => $is_finished,
                'set' => $data->test_set->title,
                'created_at' => $data->created_at,
                'total' => $data->test_set->test->full_marks,
                'obtained_marks' => $obtained_marks,
            ];
        });
        // dd($results);
        
        return view('students.test-results.index',compact('results','id'));
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
        $setuser=TestSetUser::with('test_answers')->findOrFail($id);

        if ( $setuser->user_id != auth()->user()->id ) {
            abort(401);
        }

        if ( $setuser->test_set->test->show_result != 1 ) {
            abort(401);
        }
        
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
        $marks=$setuser['test_answers']->pluck('marks')->toArray();
        $marksObtained=array_sum($marks);

        return view('students.test-results.show', compact('questions', 'type', 'set', 'answers', 'setuser', 'formattedAnswers','marks','marksObtained'));
    }

}
