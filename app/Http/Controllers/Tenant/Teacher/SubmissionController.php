<?php

namespace App\Http\Controllers\Tenant\Teacher;

use App\Exam;
use App\Answer;
use App\Helpers\NotificationHelper;
use App\SetUser;
use App\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    public function index(Exam $exam)
    {
        if ( $exam->user_id != auth()->user()->id ) {
            abort(401);
        }

        $sets = $exam->sets;
        $sections = $exam->sections;
        $setuser = SetUser::whereIn('set_id', $sets->pluck('id'))
        ->with('set', 'user:id,name', 'user.student_detail.classroom', 'user.student_detail.section')
        ->get()
        ->map(function($data) {
            $is_finished=10;

            if($data->is_finished==1) {
                $is_finished=11;
            }
            elseif($data->is_finished==0) {
                $is_finished=10;
            }

            $obtained_marks = Answer::where('set_user_id', $data->id)->sum('marks');

            return [
                'id' => $data->id,
                'user_id' => $data->user_id,
                'set_id' => $data->set_id,
                'teacher_checking' => $data->teacher_checking,
                'is_finished' => $is_finished,
                'name' => $data->user->name,
                'set' => $data->set->title,
                'roll_number' => $data->user->student_detail->roll_number,
                'classroom' => $data->user->student_detail->classroom->title,
                'section' => $data->user->student_detail->section->title,
                'created_at' => date('jS M, Y g:i a', strtotime($data->updated_at)),
                'total' => $data->set->exam->full_marks,
                'obtained_marks' => $obtained_marks,
            ];
        });;

        // dd($setuser->toArray());
        return view('teachers.submissions.index', compact('sets', 'setuser', 'sections'));
    }

    public function show(SetUser $setuser)
    {
        if ( $setuser->set->exam->user_id != auth()->user()->id ) {
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

        return view('teachers.submissions.show', compact('questions', 'type', 'set', 'answers', 'setuser', 'formattedAnswers'));
    }
    public function edit(SetUser $setuser)
    {
        if ( $setuser->set->exam->user_id != auth()->user()->id ) {
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

        return view('teachers.submissions.edit', compact('questions', 'type', 'set', 'answers', 'setuser', 'formattedAnswers'));
    }
    public function autoSave(Request $request)
    {
        $data = $request->all();

        foreach ($data as $key => $value) {
            if ((!empty($value) || $value != null) && strpos($key, 'question-') !== false) {
                $questionId = substr($key, 15);
                
                $setuser = SetUser::where('set_id', $data['set_id'])->where('user_id', $data['user_id'])->first();
                if ($setuser->teacher_checking == 1) {
                    $setuser->update(['teacher_checking' => 2]);
                }
                        
                $question = Question::where('id', $questionId)->first();

                Answer::where('set_user_id', $setuser->id)->where('question_id', $question->id)->update(['marks' => $value]);
            }
        }

        return response()->json(['result' => 'success']);
    }

    public function finish(SetUser $setuser, Request $request, NotificationHelper $notify)
    {
        if($request->question_type == "upload-pdf") {
            Answer::where('id', $request->answer_id)->update(['marks' => $request->marks]);
        }
        
        $setuser->update(['teacher_checking' => 3]);
        $exam=$setuser->set->exam;
        // if($exam->status==1){
        //     if(tenant()->plan==='large'){
        //         $notify->notifyOne($setuser->user,'App\SetUser',$setuser->id,'/results/'.$setuser->id, auth()->user()->name . ' has finished checking exam of '.$exam->title);
        
        //     }
        // }
        return redirect('/submissions/'.$setuser->set->exam_id)->with('success', 'Data saved.');
    }

    public function downloadAnswer(Answer $answer)
    {
        return Storage::disk(config('app.storage_driver'))->download('/answers/'.$answer->answer);
    }
    public function downloadQuestion(Question $question)
    {
        return Storage::disk(config('app.storage_driver'))->download('/attachments/'.$question->attachments->first()->body);
    }
}
