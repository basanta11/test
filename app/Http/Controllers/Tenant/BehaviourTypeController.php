<?php

namespace App\Http\Controllers\Tenant;

use App\BehaviourType;
use App\Http\Controllers\Controller;
use App\Section;
use App\SectionBehaviour;
use Exception;
use Illuminate\Http\Request;

class BehaviourTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $behaviourTypes=BehaviourType::whereStatus(1)->get();
        return view('admin.behaviour-types.index',compact('behaviourTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.behaviour-types.create');
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
        if(!$request->title)
            return back()->with('error','Title is required');
        $b=BehaviourType::create(['title'=>$request->title,'status'=>1]);
        return redirect('/behaviour-types')->with('success','Data saved');
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
        $behaviourType=BehaviourType::findOrFail($id);
        return view('admin.behaviour-types.edit',compact('behaviourType'));
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
        $behaviourType=BehaviourType::findOrFail($id);
        if(!$request->title)
            return back()->with('error','Title is required');
        $behaviourType->update(['title'=>$request->title]);
        return redirect('/behaviour-types')->with('success','Data updated');
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

        $behaviourType=BehaviourType::findOrFail($id);

        $behaviourType->delete();
        return redirect('/behaviour-types')->with('success','Data deleted');
    }

    public function assign()
    {
        $section=Section::with('classroom')->whereStatus(1)->get()
        ->map(function($q,$sn){
            return [
                'sn'=>$sn+=1,
                'section_id'=>$q->id,
                'section_title'=>$q->title,
                'classroom_id'=>$q['classroom']['id'],
                'classroom_title'=>$q['classroom']['title'],
            ];
        });
        return view('admin.behaviour-types.assign-behaviour',compact('section'));
    }

    public function assignEdit($id)
    {
        $sectionBehaviour=SectionBehaviour::whereStatus(1)->whereSectionId($id)->get()->pluck('behaviour_type_id')->toArray();
        $behaviourTypes=BehaviourType::whereStatus(1)->get()
        ->map(function($q) use($sectionBehaviour){
            return [
                'id'=>$q->id,
                'title'=>$q->title,
                'is_enabled'=>in_array($q->id,$sectionBehaviour) ? true : false,
            ];
        });
        
        return view('admin.behaviour-types.assign-behaviour-edit',compact('behaviourTypes','id'));
    }
    public function sectionUpdate(Request $request)
    {
        $sectionBehaviour=SectionBehaviour::whereSectionId($request->section_id)->whereBehaviourTypeId($request->behaviour_id)->first();  
        try{
            if($request->state=='true'){
                if($sectionBehaviour){
                    $sectionBehaviour->update(['status'=>1]);
                }
                else{
                    SectionBehaviour::create(['section_id'=>$request->section_id,'behaviour_type_id'=>$request->behaviour_id,'status'=>1]);
                }
            }else{
                if($sectionBehaviour){
                    $sectionBehaviour->update(['status'=>0]);
                }
            }

        }
        catch(Exception $e){
            abort(403);
        }
        return response()->json(['data'=>'success','msg'=>$request->state=='true' ? 'Enabled' : 'Disabled']);
    }
}
