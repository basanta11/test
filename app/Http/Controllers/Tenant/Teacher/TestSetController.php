<?php

namespace App\Http\Controllers\Tenant\Teacher;

use App\Http\Controllers\Controller;
use App\Test;
use App\TestQuestion;
use App\TestSet;
use Illuminate\Http\Request;

class TestSetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TestSet $testset)
    {
        //
        $test=Test::whereId($testset->test_id)->with(['lesson','lesson.course'])->first();
        $lesson=$test['lesson'];

        $testQuestion=TestQuestion::whereTestSetId($testset->id); 
        $hasQuestions=$testQuestion->get()->count();
        $hasPdf=$testQuestion->whereType(0)->get()->count() >0 ;
        return view('teachers.testsets.index',compact('testset','lesson','hasQuestions','hasPdf'));
    }


    public function hasQuestions(TestSet $testset)
    {

        $status=TestQuestion::whereTestSetId($testset->id)->get()->count() > 0; 
        return response()->json(['status'=>$status]);
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
        //
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
        $testset=TestSet::findOrFail($id);
        $testset->delete();

        return response()->json(['result' => 'success']);
    }

    public function deleteSet(TestSet $testset)
    {
        $test=Test::whereId($testset->test_id)->first();
        // dd($testset);
        if($test->test_start < now()->addHours(3)){
            return back()->with('error','You cannot delete this set.');
        }
        if($test->testSets()->get()->count()==1){
            return redirect('/tests/'.$test->id.'/view')->with('error','At least one set has to be present in test.');
        }        
        $testset->delete();
        return redirect('/tests/'.$test->id.'/view')->with('success','Successfully deleted');
    }
    
    public function getQuestions($id)
    {
        return TestQuestion::where('test_set_id', $id)->where('status', 1)->orderBy('order', 'asc')->get();
    }
}
