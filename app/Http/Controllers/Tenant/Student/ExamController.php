<?php

namespace App\Http\Controllers\Tenant\Student;

use App\Set;
use App\Exam;
use App\User;
use DateTime;
use App\Answer;
use App\Section;
use App\SetUser;
use App\Question;
use DateInterval;
use App\Helpers\FileHelper;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ExamController extends Controller
{
    public function index()
    {
        $sn = 0;

        $section = auth()->user()->student_detail->section_id;

        $exams = Exam::with(['sections'=>function($q) use($section){
            $q->where('sections.id',$section);
        }, 'sections.classroom','sets','course'])
        ->whereStatus(1)
        ->whereHas('sections',function($q) use($section){
            $q->where('sections.id',$section);
        })
        ->get()
        ->map(function($data, $sn) {
            $status='10';
            if($data->status == 1)
            {
                $status='11';
            }elseif($data->status == 2){
                $status='12';
            }

            if($data->type == 0) {
                $terminal = '1';
            }
            else if($data->type == 1) {
                $terminal = '2';
            }
            else {
                $terminal = '3';
            }

            $setuser = SetUser::whereIn('set_id', $data->sets->pluck('id'))->where('user_id', auth()->user()->id)->first();

            $is_finished = 'Not Started';
            if ($setuser) {
                if($setuser->is_finished == 0) {
                    $is_finished = 'Incomplete';
                }
                elseif($setuser->is_finished == 1){
                    $is_finished = 'Finished';
                }
            }
            else {
                $is_finished = 'Not Started';
            }
           
            return [
                'sn' => $sn+=1,
                'id' => $data->id,
                'title' => $data->title,
                'course'=>$data['course']['title'],
                'exam_start' => date('jS M, Y g:i a', strtotime($data->exam_start)),
                'full_marks' => $data->full_marks,
                'pass_marks' => $data->pass_marks,
                'duration' => $data->duration.' minutes',
                'terminal' => $terminal,
                'is_finished' => $is_finished,
                'status' => $status,
                'created_at' => $data->created_at,
            ];
        });

        return view('students.exams.index', compact('exams'));
    }

    public function show(Exam $exam)
    {
        if ( !in_array(auth()->user()->student_detail->section_id, $exam->sections()->pluck('sections.id')->toArray()) ) {
            abort(401);
        }
        $setuser = SetUser::whereIn('set_id', $exam->sets->pluck('id'))->where('user_id', auth()->user()->id)->first();

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
        $currenttime = $current = new DateTime($exam->exam_start);
        $minutes = (int) $exam->duration;
        $endtime = $currenttime->modify('+'.$minutes.' minutes')->format('Y-m-d H:i:s');

        return view('students.exams.show', compact('exam', 'endtime','is_finished'));
    }

    public function start(Exam $exam)
    {
        if ( !in_array(auth()->user()->student_detail->section_id, $exam->sections()->pluck('sections.id')->toArray()) ) {
            abort(401);
        }
        
        $currenttime = $current = new DateTime($exam->exam_start);
        $minutes = (int) $exam->duration;
        $endtime = $currenttime->modify('+'.$minutes.' minutes')->format('Y-m-d H:i:s');

        if ( date('Y-m-d H:i:s') > $endtime || date('Y-m-d H:i:s') < $exam->exam_start ) {
            abort(403, 'You cannot start this exam now.');
        }

        $exam_sets = $exam->sets->pluck('id');
        $answers = collect();

        if ( SetUser::where('user_id', auth()->user()->id)->whereIn('set_id', $exam_sets)->exists() ) {
            $setuser = SetUser::where('user_id', auth()->user()->id)->whereIn('set_id', $exam_sets)->first();
            $set = Set::where('id', $setuser->set_id)->first();

            $answers = Answer::where('set_user_id', $setuser->id)->get();
        }
        else {
            $set = $exam->sets->random();
            SetUser::create([
                'set_id' => $set->id,
                'user_id' => auth()->user()->id
            ]);
        }

        $questions = Question::where('set_id', $set->id)->orderBy('order', 'asc')->with(['question_options'])->get();

        if ($questions->isEmpty()) {
            return back()->with('error', 'This question paper does not have any questions!');
        }

        if ($questions->first()->type == 0) {
            $type = 'PDF';
        }
        else {
            $type = 'Created';
        }


        return view('students.exams.start', compact('exam', 'questions', 'type', 'set', 'answers', 'endtime'));
    }

    public function downloadQuestion(Question $question, User $user)
    {
        if ( !SetUser::where('set_id', $question->set_id)->where('user_id', $user->id)->exists() ) {
            return back()->with('error', 'You do not have permission to download this attachment.');
        }

        return Storage::disk(config('app.storage_driver'))->download('/attachments/'.$question->attachments->first()->body);
    }

    public function finish(Request $request, FileHelper $file, NotificationHelper $notify)
    {
        if ( !SetUser::where('set_id', $request->set_id)->where('user_id', auth()->user()->id)->exists() ) {
            return back()->with('error', 'Something went wrong.');
        }

        $setuser = SetUser::where('set_id', $request->set_id)->where('user_id', auth()->user()->id)->first();

        if ($request->question_type == "upload-pdf") {
            if ($request->upload_answer) {
                $filename = $file->storeFile($request->upload_answer, 'answers');
            }
            else {
                $filename = null;
            }

            Answer::create([
                'set_user_id' => $setuser->id,
                'question_id' => $request->question_id,
                'answer' => $filename
            ]);
        }
        
        $setuser->update(['is_finished' => 1]);
        if(tenant()->plan==='large'){
            $notify->notifyOne($setuser->set->exam->user, 'App\SetUser',$setuser->id,'/submissions/'.$setuser->id.'/show', auth()->user()->name. ' has submitted exam. Title: '.$setuser['set']['exam']['title']);
        }
        return redirect('/exam-students')->with('success', 'Exam submission successful.');
    }

    public function autoSave(Request $request)
    {
        if ( !SetUser::where('set_id', $request->set_id)->where('user_id', auth()->user()->id)->exists() ) {
            return back()->with('error', 'Something went wrong.');
        }

        $data = $request->all();

        foreach ($data as $key => $value) {
            if ((!empty($value) || $value != null) && strpos($key, 'question-') !== false) {
                $questionId = substr($key, 9);
                
                $setuser = SetUser::where('set_id', $data['set_id'])->where('user_id', auth()->user()->id)->first();
                $question = Question::where('id', $questionId)->first();

                if ( Answer::where('question_id', $questionId)->where('set_user_id', $setuser->id)->exists() ) {
                    $answer = Answer::where('question_id', $questionId)->where('set_user_id', $setuser->id)->first();

                    if ($question->type == 1) {
                        $answer->update([
                            'set_user_id' => $setuser->id,
                            'question_id' => $questionId,
                            'question_option_id' => $value
                        ]);
                    }
                    else if ($question->type == 2) {
                        $answer = Answer::where('question_id', $questionId)->where('set_user_id', $setuser->id)->delete();
                        
                        foreach ($value as $v) {
                            $answer = Answer::create([
                                'set_user_id' => $setuser->id,
                                'question_id' => $questionId,
                                'question_option_id' => $v
                            ]);
                        }
                    }
                    else {
                        $answer->update([
                            'set_user_id' => $setuser->id,
                            'question_id' => $questionId,
                            'answer' => $value
                        ]);
                    }
                }
                else {
                    if ($question->type == 1) {
                        $answer = Answer::create([
                            'set_user_id' => $setuser->id,
                            'question_id' => $questionId,
                            'question_option_id' => $value
                        ]);
                    }
                    else if ($question->type == 2) {
                        foreach ($value as $v) {
                            $answer = Answer::create([
                                'set_user_id' => $setuser->id,
                                'question_id' => $questionId,
                                'question_option_id' => $v
                            ]);
                        }
                    }
                    else {
                        $answer = Answer::create([
                            'set_user_id' => $setuser->id,
                            'question_id' => $questionId,
                            'answer' => $value
                        ]);
                    }
                }
            }
        }

        return response()->json(['result' => $request->all()]);
    }
    public function showResult($id)
    {
        $set=Set::with(['exam'])->findOrFail($id);
        $set_user=SetUser::with(['answers'])->whereUserId(auth()->user()->id)->whereSetId($set->id)->first();
        $marks=$set_user['answers']->pluck('marks')->toArray();
        $marksObtained=array_sum($marks);
        return view('students.exams.result',compact('set','marksObtained'));
    }
}
