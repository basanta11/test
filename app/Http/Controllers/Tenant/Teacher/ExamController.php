<?php

namespace App\Http\Controllers\Tenant\Teacher;

use App\Exam;
use App\Http\Controllers\Controller;
use App\Section;
use App\Set;
use Illuminate\Http\Request;

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
        $exams = Exam::where('user_id', auth()->user()->id)->whereStatus(1)
        ->get()
        ->map(function($data, $sn) {
            $examType='N/A';
            if($data->type==0){
                $examType='1st Terminal';
            }elseif($data->type==1){
                $examType='2nd Terminal';
            }elseif($data->type==2){
                $examType='3rd Terminal';
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
                'sections' => $data->sections,
                'type'=>$examType,
                'classroom' => $data->sections->first()->classroom->title,
                'sets'=>$data->sets,
                'status' => $data->status+10,
                'created_at' => $data->created_at,
            ];
        });
        // dd($exams);
        return view('teachers.exams.index', compact('exams'));
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
        $exam = Exam::with(['course.classroom'])->findOrFail($id);

        if ( $exam->user_id != auth()->user()->id ) {
            abort(401);
        }

        $sets = Set::whereExamId($exam->id)
            ->get()
            ->map(function($data) {
                if(!$data->questions->isEmpty()) {
                    $type = $data->questions->first()->type == 0 ? 'Upload PDF' : 'Created';
                }
                else {
                    $type = 'No questions';
                }

                return [
                    'id' => $data->id,
                    'title' => $data->title,
                    'type' => $type,
                    'created_at' => $data->created_at,
                ];
            });

        return view('teachers.exams.show', compact('exam','sets'));
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
