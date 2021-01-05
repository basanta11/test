<?php

namespace App\Http\Controllers\Tenant;
use App\Section;
use App\SectionBehaviour;
use App\BehaviourType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClassTeacherController extends Controller
{
    //
    public function assignBehaviour()
    {
        $section=Section::with('classroom')->whereStatus(1)->whereUserId(auth()->user()->id)->get()
        ->map(function($q,$sn){
            return [
                'sn'=>$sn+=1,
                'section_id'=>$q->id,
                'section_title'=>$q->title,
                'classroom_id'=>$q['classroom']['id'],
                'classroom_title'=>$q['classroom']['title'],
            ];
        });
        return view('teachers.behaviour-types.assign-behaviour',compact('section'));
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

    public function assignEdit($id)
    {
        if(Section::findOrFail($id)->user_id!=auth()->user()->id){
            abort(403);
        }

        $sectionBehaviour=SectionBehaviour::whereStatus(1)->whereSectionId($id)->get()->pluck('behaviour_type_id')->toArray();
        $behaviourTypes=BehaviourType::whereStatus(1)->get()
        ->map(function($q) use($sectionBehaviour){
            return [
                'id'=>$q->id,
                'title'=>$q->title,
                'is_enabled'=>in_array($q->id,$sectionBehaviour) ? true : false,
            ];
        });
        
        return view('teachers.behaviour-types.assign-behaviour-edit',compact('behaviourTypes','id'));
    }
}
