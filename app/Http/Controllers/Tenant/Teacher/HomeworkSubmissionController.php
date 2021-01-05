<?php

namespace App\Http\Controllers\Tenant\Teacher;

use App\Attachment;
use App\Helpers\FileHelper;
use App\Helpers\NotificationHelper;
use App\Homework;
use App\HomeworkUser;
use App\Http\Controllers\Controller;
use ZipArchive;
use App\User;
use Http;
use Illuminate\Http\Request;
use Storage;

use Str;

class HomeworkSubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        //
        $homework=Homework::with(['homework_section'])->findOrFail($id);
        
        if ( $homework->created_by!=auth()->user()->id)
            abort(403);

        $sections=$homework['homework_section']->pluck('section_id')->toArray();
        $students=User::whereStatus(1)->with(['student_detail.section','homework_user'=>function($d) use($id){
            $d->whereHomeworkId($id);
        }])->whereHas('student_detail', function($q) use($sections){
            $q->whereIn('section_id',$sections);
        })->get()->map(function ($q, $sn) use($homework){
            $homeworkStatus=$homeworkUser=$date='';
            if($homework->due_date_time){

            }else{
                $homeworkStatus='No Due Date';
                $date='Not Submitted';
            }

            if($q['homework_user']){
                // dd($['homework_user']);
                if($q['homework_user']['created_at']>$homework->due_date_time && $homework->due_date_time){
                    $homeworkStatus='Delayed';
                }else{
                    $homeworkStatus='Timely Submitted';
                }
                $homeworkUser=$q['homework_user']['id'];
                $date=date('jS M, Y g:i a', strtotime($q['homework_user']['created_at']));
            }else{
                $homeworkStatus='Not Submited';
                $homeworkUser=null;
                $date='Pending';
            }
            return [
                'sn'=>$sn+=1,
                'homework_user_id'=>$homeworkUser,
                'id'=>$q->id,
                'name'=>$q->name,
                'section'=>$q['student_detail']['section']['title'],
                'homework_status'=> $homeworkStatus,
                'date'=>$date,
            ];
        })->sortBy('section');
        return view('teachers.homework-submissions.index',compact('students'));

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
    public function show($id, FileHelper $file)
    {
        //
        $homeworkUser=HomeworkUser::findOrFail($id);
        $homework=$homeworkUser->homework;

        if ( $homework->created_by!=auth()->user()->id)
            abort(403);

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
        return view('teachers.homework-submissions.show',compact('homeworkUser','attachments','homework','attachmentsAnswer'));

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
        $homeworkUser=HomeworkUser::with(['homework'])->findOrFail($id);
        $homework=$homeworkUser->homework;

        if ( $homework->created_by!=auth()->user()->id)
            abort(403);
            
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
        return view('teachers.homework-submissions.edit',compact('homeworkUser','attachments','homework','attachmentsAnswer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, NotificationHelper $notify)
    {
        //
        $homeworkUser=HomeworkUser::with(['user'])->findOrFail($id);
        $homeworkUser->update(['obtained_marks'=>$request->obtained_marks]);
        if(tenant()->plan==='large'){
            $notify->notifyOne($homeworkUser['user'],'App\HomeworkSubmission',$homeworkUser->id,'/my-homeworks/'.$homeworkUser->homework->id, auth()->user()->name. ' has checked your homework. Title: '.$homeworkUser->homework->title);
        }
        return redirect('/homework-submissions/'.$homeworkUser->homework_id)->with('success','Data updated.');
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
    public function download(Attachment $attachment, FileHelper $file)
    {
        if($file->fileExists('attachments',$attachment->body))
            return Storage::disk(config('app.storage_driver'))->download('/attachments/'.$attachment->body);
        else abort(403);
    }
    
    public function downloadAll($id, FileHelper $file)
    {
        $homeworkUser=HomeworkUser::findOrFail($id);
        $zip = new ZipArchive;
        $file_prefix=$homeworkUser->user->name.'-'.str_replace(' ','_',$homeworkUser->homework->course->title);
        $fileName = $file_prefix.Str::uuid().'-attachments.zip';
        $files = $homeworkUser->attachments;
        if($files->filter(function ($value) use($file) { return $file->fileExists('attachments',$value->body); } )->count()){
            if ($zip->open($fileName, ZipArchive::CREATE) === TRUE)
            {
                
                foreach ($files as $file) {
                    $zip->addFile(public_path('storage/' . config('app.filesystem_suffix') . tenant()->id . '/attachments/' . $file->body), $file->body);
                }
                $zip->close();
            }
            return response()->download(public_path($fileName))->deleteFileAfterSend();
        }else abort(403);
    }
}
