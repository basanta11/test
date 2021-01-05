<?php

namespace App\Http\Controllers\Tenant\Student;

use App\Attachment;
use App\Helpers\FileHelper;
use App\Helpers\NotificationHelper;
use App\Homework;
use App\HomeworkUser;
use App\Http\Controllers\Controller;
use App\StudentDetail;
use DB;
use Exception;
use Http;
use Illuminate\Http\Request;
use Storage;
use Str;
use ZipArchive;

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
        $homeworks=Homework::with(['course','homework_section.section.student_details','homework_users'=>function($q){
            $q->where('homework_user.user_id',auth()->user()->id);
        }])->whereHas('homework_section.section.student_details',function($q){
            $q->whereUserId(auth()->user()->id);
        })->get()->map(function($q,$sn){
            $status=$hasHomework='';
            $isMarked='Not Marked';
            if(!$q['homework_users']->isEmpty()){
                if($q['homework_users']->first()['created_at']>$q->due_date_time && $q->due_date_time){
                    $status='Late Submission';
                }else{
                    $status='Submitted';
                }
                if($q['homework_users']->first()['obtained_marks']){
                    $isMarked='Marked';
                }
                $hasHomework=true;
            }else{
                $status='Not Submited';
                $hasHomework=false;
            }
            return [
                'sn'=>$sn+=1,
                'id'=>$q->id,
                'title'=>$q->title,
                'course_title'=>$q['course']['title'],
                'due_date'=>$q->due_date_time ? date('jS M, Y g:i a', strtotime($q->due_date_time)) : 'No Due Date',
                'status'=>$status,
                'obtained_marks'=>$q->obtained_marks,
                'hasHomework'=>$hasHomework,
                'marked'=>$isMarked,
            ];
        });
        return view('students.homeworks.index',compact('homeworks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id, FileHelper $file)
    {
        //
        $homework=Homework::with(['course','homework_section.section.student_details','homework_users'])->findOrFail($id);
        
        if(empty(array_intersect(StudentDetail::whereUserId(auth()->user()->id)->pluck('section_id')->toArray(),
            $homework['homework_section']->pluck('section')->pluck('id')->toArray()
        ))){
            abort(403);
        }
        if(!HomeworkUser::whereHomeworkId($id)->whereUserId(auth()->user()->id)->get()->isEmpty()){
            return redirect('/my-homeworks/'.$id.'/edit');
        }
        $attachments=$homework->attachments()->get()->map(function($data)use($file){
            if($file->fileExists('attachments',$data->body)){
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
            }
        })->filter(function ($value) { return !is_null($value); });
       
        return view('students.homeworks.create',compact('homework','attachments'));
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
        $homework=Homework::with(['teacher'])->findOrFail($request->homework_id);
        DB::beginTransaction();

        try{
            $homeworkUser=HomeworkUser::create([
                'homework_id'=>$request->homework_id,
                'answer'=>$request->submission,
                'status'=>1,
                'user_id'=>auth()->user()->id,
            ]);
            if($request->attachments){
                foreach(explode(',',$request->attachments) as $attach){
                    // array_push($arr,new Attachment(['body'=>$attach]));
                    $homeworkUser->attachments()->save(new Attachment(['body'=>$attach]));
                }
            }
            DB::commit();
            if(tenant()->plan==='large'){
                $notify->notifyOne(
                    $homework['teacher'],
                    'App\HomeworkUser',
                    $homeworkUser->id,
                    '/homework-submissions/'.$homeworkUser->id.'/show',
                    auth()->user()->name .' has submitted homework of '.$homework->title
                );
            }

        }catch(Exception $e){
            DB::rollback();
            dd($e);
        }
        return redirect('/my-homeworks')->with('success','Data saved');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, FileHelper $file)
    {
        //
        $homework=Homework::with(['course','homework_section.section.student_details','homework_users'])->findOrFail($id);
        $homeworkUser=HomeworkUser::whereHomeworkId($homework->id)->first();
        
        if(empty(array_intersect(StudentDetail::whereUserId(auth()->user()->id)->pluck('section_id')->toArray(),
            $homework['homework_section']->pluck('section')->pluck('id')->toArray()
        ))){
            abort(403);
        }
        if(HomeworkUser::whereHomeworkId($id)->whereUserId(auth()->user()->id)->get()->isEmpty()){
            return redirect('/my-homeworks/'.$id.'/create');
        }
        $attachments=$homework->attachments()->get()->map(function($data) use ($file){
            if($file->fileExists('attachments',$data->body)){
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
            }
        })->filter(function ($value) { return !is_null($value); });;

        $attachmentsAnswer=$homeworkUser->attachments()->get()->map(function($data)use($file){
            if($file->fileExists('attachments',$data->body)){
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
            }
        })->filter(function ($value) { return !is_null($value); });;


        return view('students.homeworks.show',compact('homeworkUser','homework','attachments','attachmentsAnswer'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, FileHelper $file)
    {
        //
        $homework=Homework::with(['course','homework_section.section.student_details','homework_users'])->findOrFail($id);
        $homeworkUser=HomeworkUser::whereHomeworkId($homework->id)->first();
        
        if(empty(array_intersect(StudentDetail::whereUserId(auth()->user()->id)->pluck('section_id')->toArray(),
            $homework['homework_section']->pluck('section')->pluck('id')->toArray()
        ))){
            abort(403);
        }
        if(HomeworkUser::whereHomeworkId($id)->whereUserId(auth()->user()->id)->get()->isEmpty()){
            return redirect('/my-homeworks/'.$id.'/create');
        }
        $attachments=$homework->attachments()->get()->map(function($data) use($file){
            if($file->fileExists('attachments',$data->body)){

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
            }
        })->filter(function ($value) { return !is_null($value); });;

        $attachmentsAnswer=$homeworkUser->attachments()->get()->map(function($data) use($file){
            if($file->fileExists('attachments',$data->body)){
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
            }
        })->filter(function ($value) { return !is_null($value); });

        return view('students.homeworks.edit',compact('homeworkUser','homework','attachments','attachmentsAnswer'));
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
        $homeworkUser=HomeworkUser::findOrFail($id);
        if ( $homeworkUser->user_id!=auth()->user()->id)
            abort(403);
        $homeworkUser->update(['answer'=>$request->submission]);
        
        $prevAttachments=$homeworkUser->attachments()->pluck('body')->toArray();

        foreach(explode(',',$request->attachments) as $attach){
            if(!in_array($attach, $prevAttachments)){
                $homeworkUser->attachments()->save(new Attachment(['body'=>$attach]));
            }
        }

        return redirect('/my-homeworks')->with('success','Data updated');
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
    public function download(Attachment $attachment)
    {
        if($file->fileExists('attachments',$attachment->body))
            return Storage::disk(config('app.storage_driver'))->download('/attachments/'.$attachment->body);
        else abort(403);
    }
    
    public function downloadAll(Homework $homework, FileHelper $file)
    {
        $zip = new ZipArchive;
   
        $fileName = Str::uuid().'-attachments.zip';
        $files = $homework->attachments;
        if($files->filter(function ($value) use($file) { return $file->fileExists('attachments',$value->body); } )->count()){

            if ($zip->open(public_path($fileName), ZipArchive::CREATE) === TRUE)
            {
                $files = $homework->attachments;
                
                foreach ($files as $file) {
                    $zip->addFile(public_path('storage/' . config('app.filesystem_suffix') . tenant()->id . '/attachments/' . $file->body), $file->body);
                }
                
                $zip->close();
            }
            return response()->download(public_path($fileName))->deleteFileAfterSend();
        }else{
            abort (403);        }
    
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
