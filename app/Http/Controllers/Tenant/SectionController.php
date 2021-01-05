<?php

namespace App\Http\Controllers\Tenant;

use App\CourseDetail;
use App\Http\Controllers\Controller;
use App\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sn = 0;
        $sections = Section::with('classroom')->get()->map(function($data,$sn) {
            return [
                'sn' => $sn += 1,
                'id' => $data->id,
                'title' => $data->title,
                'classroom' => $data->classroom->title,
                'classroom_id' => $data->classroom_id,
                'status' => $data->status == 1 ? 11 : 10,
            ];
        });

        return view('admin.sections.index', compact('sections'));
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
     * @param  \App\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function show(Section $section)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function edit(Section $section)
    {
        return view('admin.sections.edit', compact('section'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Section $section)
    {
        request()->validate([
            'title' => ['required', 'string', 'max:255']
        ]);

        $section->update([
            'title' => $request->title
        ]);

        return redirect('/sections')->with('success', 'Data updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function destroy(Section $section)
    {
        //
    }

    // $status -> send 1 to active, 2 to deactivate
    public function statusControl($id,$status)
    {
        if(!in_array($status,[0,1])){
            return back()->with('error','Action cannot be recognized. Please try again.');
        }
        $message=$status==1 ? 'Section activated.': 'Section deactivated' ;
        $section=Section::findOrFail($id);

        $section->update(['status'=>$status]);

        return back()->with('success',$message);
    }

    // $status -> send 1 to active, 2 to deactivate
    public function statusControlBulk(Request $request,$status)
    {
        if(!in_array($status,[0,1]) || !json_decode($request->list)){
            return back()->with('error','Action cannot be recognized. Please try again.');
        }

        $message=$status==1 ? 'Section(s) activated.': 'Section(s) deactivated' ;
        $section = Section::whereIn('id',json_decode($request->list))->update(['status'=>$status]);

        return back()->with('success',$message);
    }
}
