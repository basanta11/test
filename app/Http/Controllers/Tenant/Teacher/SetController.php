<?php

namespace App\Http\Controllers\Tenant\Teacher;

use App\Set;
use App\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SetController extends Controller
{
    public function show(Set $set)
    {
        if ( $set->exam->user_id != auth()->user()->id ) {
            abort(401);
        }
        
        $questions=Question::whereSetId($set->id); 

        $hasQuestions=$questions->get()->count();
        $hasPdf=$questions->whereType(0)->get()->count() >0 ;
        
        return view('teachers.sets.show', compact('set','hasPdf','hasQuestions'));
    }

    public function hasQuestions(Set $set)
    {
        $status=Question::wheresetId($set->id)->get()->count() > 0; 
        return response()->json(['status'=>$status]);
    }

    public function destroy(Set $set)
    {
        if($set->exam->exam_start < now()->addHours(3)){
            return back()->with('error','You cannot delete this set.');
        }
        $set->delete();

        return response()->json(['result' => 'success']);
    }

    public function getQuestions($id)
    {
        return Question::where('set_id', $id)->where('status', 1)->orderBy('order', 'asc')->get();
    }
}
