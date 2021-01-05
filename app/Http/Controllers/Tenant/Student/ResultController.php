<?php

namespace App\Http\Controllers\Tenant\Student;

use App\Answer;
use App\Http\Controllers\Controller;
use App\Question;
use App\SetUser;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $results = SetUser::where('user_id', auth()->user()->id)->whereTeacherChecking(3)
        ->with('set', 'set.exam','set.exam.course')->whereHas('set.exam',function($q){
            $q->whereShowResult(1);
        })
        ->get()
        ->map(function($data) {
            $is_finished='Not Started';

            if($data->is_finished==1) {
                $is_finished='Finished';
            }
            elseif($data->is_finished==0) {
                $is_finished='Incomplete';
            }

            if($data['set']['exam']['type'] == 0) {
                $examType = '1';
            }
            else if($data->type == 1) {
                $examType = '2';
            }
            else {
                $examType = '3';
            }

            $obtained_marks = Answer::where('set_user_id', $data->id)->sum('marks');

            return [
                'id' => $data->id,
                'user_id' => $data->user_id,
                'set_id' => $data->set_id,
                'exam_id'=>$data['set']['exam']['id'],
                'exam_title'=>$data['set']['exam']['title'],
                'exam_type'=>$examType,
                'course_id'=>$data['set']['exam']['course']['id'],
                'course_title'=>$data['set']['exam']['course']['title'],
                'is_finished' => $is_finished,
                'set' => $data->set->title,
                'created_at' => $data->created_at,
                'total' => $data->set->exam->full_marks,
                'pass_marks' => $data->set->exam->pass_marks,
                'obtained_marks' => $obtained_marks,
            ];
        });
        
        return view('students.results.index',compact('results'));
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $setuser=SetUser::with('answers')->findOrFail($id);

        if ( $setuser->user_id != auth()->user()->id ) {
            abort(401);
        }

        if ( $setuser->set->exam->show_result != 1 ) {
            abort(401);
        }

        $formattedAnswers = [];
        $set = $setuser->set;
        $questions = Question::where('set_id', $set->id)->orderBy('order', 'asc')->with(['question_options'])->get();

        if ($questions->first()->type == 0) {
            $type = 'PDF';
        }
        else {
            $type = 'Created';
        }

        $answers = Answer::where('set_user_id', $setuser->id)->get();
        foreach ($answers as $key => $value) {
            $formattedAnswers[$value->question_id]['question_option_id'] = $value->question_option_id;
            $formattedAnswers[$value->question_id]['answer'] = $value->answer;
            $formattedAnswers[$value->question_id]['marks'] = $value->marks;
        }
        $marks=$setuser['answers']->pluck('marks')->toArray();
        $marksObtained=array_sum($marks);

        return view('students.results.show', compact('questions', 'type', 'set', 'answers', 'setuser', 'formattedAnswers','marks','marksObtained'));
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
