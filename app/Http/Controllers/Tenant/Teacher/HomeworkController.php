<?php

namespace App\Http\Controllers\Tenant\Teacher;

use App\Attachment;
use App\Course;
use App\CourseDetail;
use App\Helpers\FileHelper;
use App\Helpers\NotificationHelper;
use App\Homework;
use App\HomeworkQuestion;
use App\HomeworkSection;
use App\Http\Controllers\Controller;
use App\Notifications\ActionNotification;
use App\User;
use DB;
use Exception;
use Http;
use Illuminate\Http\Request;
use Storage;
use Str;

class HomeworkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $homeworks=Homework::with('course','homework_section.section')->whereCreatedBy(auth()->user()->id)->get()->map(function($q,$sn){
            return [
                'sn'=>$sn+=1,
                'id'=>$q->id,
                'title'=>$q->title,
                'full_marks'=>$q->full_marks,
                'pass_marks'=>$q->pass_marks,
                'due_date'=>$q->due_date_time ? date('jS M, Y g:i a', strtotime($q->due_date_time)) : 'No Due Date',
                'sections'=>array_map(function($data){
                    return $data['section'];
                },
                $q['homework_section']->toArray()),
                'course_id'=>$q->course_id,
                'course_title'=>$q['course']['title'],
                'status'=>$q->status,
            ];
        });

        return view('teachers.homeworks.index',compact('homeworks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $courses=Course::with('course_details')->whereHas('course_details',function($q){
            $q->whereUserId(auth()->user()->id);
        })->get();
        return view('teachers.homeworks.create',compact('courses'));
    }
    public function getSections(Course $course)
    {
        $sections=CourseDetail::with(['section'])->whereCourseId($course->id)->whereUserId(auth()->user()->id)->get();
        return response()->json(['data'=>$sections]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, NotificationHelper $notify)
    {
        //
        // dd($request->all());
        $request->merge(['status' => 1,'created_by'=>auth()->user()->id]);
        $getSets = explode(",", $request->sets);
        $section_id=$request->section_id;
        DB::beginTransaction();
        try {
            $homework = Homework::create($request->only([
                'title','full_marks','pass_marks','due_date_time' ,'course_id','status','created_by', 'question'
            ]));
            $homework->homework_section()->insert(array_map(function($data) use($homework){
                return [
                    'section_id'=>$data,
                    'homework_id'=>$homework->id,
                ];
            },$request->section_id));

            if($request->attachments){
                foreach(explode(',',$request->attachments) as $attach){
                    // array_push($arr,new Attachment(['body'=>$attach]));
                    $homework->attachments()->save(new Attachment(['body'=>$attach]));
                }
            }
            DB::commit();
            $users_section=User::with('student_detail')->whereHas('student_detail',function($q) use($section_id,$homework){
                $q->whereIn('section_id',$section_id);
            })->get();
            if(tenant()->plan==='large'){
                $notify->notifyMany($users_section, 'App\Homework',$homework->id,'/my-homeworks/'.$homework->id,auth()->user()->name .' has added homework. Title: '. $homework->title);
            }
        }
        catch (Exception $e) {
            DB::rollback();
            dd($e);
            return back()->with('error', $e);
        }
        
        // ActionNotification::

        return redirect('/homeworks')->with('success','Data saved');

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
        $homework=Homework::findOrFail($id);
        if ( $homework->created_by!=auth()->user()->id)
            abort(403);
        // $sn=0;
        $questions=$homework->homework_questions->map(function($q,$sn){
            return [
                'sn'=>$sn+=1,
                'id'=>$q->id,
                'status'=>$q->status,
                'title'=>$q->title,
            ];
        });;
        return view('teachers.homework-questions.index',compact('questions','id'));
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
        $homework=Homework::with('course','homework_section.section')->findOrFail($id);
        if ( $homework->created_by!=auth()->user()->id)
            abort(403);
        $courses=Course::with('course_details')->whereHas('course_details',function($q){
            $q->whereUserId(auth()->user()->id);
        })->get();
        $sections=CourseDetail::with(['section'])->whereCourseId($homework->course_id)->whereUserId(auth()->user()->id)->get();
        
        $homework_sections=HomeworkSection::whereHomeworkId($homework->id)->pluck('section_id')->toArray();


        stream_context_set_default( [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);
        $attachments=$homework->attachments()->get()->map(function($data){
            $url=Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/attachments/'. $data->body);
            $url_fix=Http::get($url);
            switch ($url_fix->headers()["Content-Type"][0]) {
                case 'application/pdf':
                    $url= global_asset("assets/media/files/png/pdf.png");
                    break;
                case 'application/msword':

                    $url= global_asset("assets/media/files/png/word.png");
                    break;
                case 'application/vnd.ms-powerpoint':
                    $url= global_asset("assets/media/files/png/powerpoint.png");
                    break;
                case 'application/vnd.ms-excel':
                    $url= global_asset("assets/media/files/png/excel.png");
                    break;
                case 'text/plain':

                    $url= global_asset("assets/media/files/png/txt.png");
                    break;
                case fnmatch("image*", $url_fix->headers()["Content-Type"][0]):
                    $url=Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/attachments/'. $data->body);
                    break;
                default:
                    $url= global_asset("assets/media/files/png/document.png");
                    break;
            }
            return [
                'id'=>$data->id,
                'serverName'=>$data->body,
                'location'=>$url,
                'size'=>isset($url_fix->headers()["Content-Length"][0]) ? $url_fix->headers()["Content-Length"][0] : null,
            ];
        });

        return view('teachers.homeworks.edit',compact('homework','courses','sections','homework_sections','attachments'));

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

       
        $section_ids=$request->section_id;
        $homework=Homework::findOrFail($id);
        if ( $homework->created_by!=auth()->user()->id)
            abort(403);
        if(!isset($request->has_due)){
            $request['due_date_time']=null;
        }
        $homework->update($request->only([
            'title','full_marks','due_date_time','question'
        ]));
            
        $old_sections=$homework->homework_section()->pluck('section_id')->toArray();
        $new_sections=array_filter($section_ids,function($data) use($old_sections){
            return !in_array($data,$old_sections) ;
        });
        $not_old_sections=array_filter($old_sections,function($data) use($section_ids){
            return !in_array($data,$section_ids) ;
        });

        
        $prevAttachments=$homework->attachments()->pluck('body')->toArray();
        HomeworkSection::whereHomeworkId($homework->id)->whereIn('section_id',$not_old_sections)->delete();
        
        HomeworkSection::insert(array_map(function($data) use($homework){
            return [
                'section_id'=>$data,
                'homework_id'=>$homework->id,
            ];
        },$new_sections));
        foreach(explode(',',$request->attachments) as $attach){
            if(!in_array($attach, $prevAttachments)){
                $homework->attachments()->save(new Attachment(['body'=>$attach]));
            }
        }

        return redirect('/homeworks')->with('success','Data updated');
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
        $homework=Homework::findOrFail($id);
        if($homework->due_date_time < now()->addHours(3)){
            return back()->with('error','You cannot delete this homework.');
        }
        $homework->delete();
        return redirect('/homeworks')->with('success','Data deleted.');

    }

    public function uploadDropzone(Request $request)
    {
        $getFile=$request->file;
        if ( isset($getFile) && !empty($getFile) ) {
            // $folder = $request->type;
            $folder = $request->type;
            $fileName =$folder.'-'. Str::uuid(). '.' . $getFile->getClientOriginalExtension();
            Storage::disk(config('app.storage_driver'))->putFileAs(
                $folder, $getFile, $fileName
            );
        }
        else {
            return response()->json(['result' => 'failure']);
        }

        return response()->json(['sucess' => true, 'filename' => $fileName]);
    }
    public function removeFile($folder, $filename, FileHelper $file)
    {
        return $file->deleteFile($folder, $filename);
    }

    public function removeFileAndAttachment($folder, $filename, $id, FileHelper $file)
    {
        $attachment=Attachment::whereId($id)->first();
        if($attachment)
            $attachment->delete();
        return $file->deleteFile($folder, $filename);
    }
}
