<?php

namespace App\Http\Controllers\Tenant;

use App\Helpers\FileHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Lesson;
use App\Topic;
use App\TopicAttachment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Str;

class TopicController extends Controller
{
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        //
        $lesson=Lesson::with(['course'])->findOrFail($id);
        return view('admin.topics.create',compact('lesson'));
    }

    public function createAttach($id)
    {
        //
        $topic=Topic::with(['lesson','lesson.course'])->findOrFail($id);
        $lesson=$topic['lesson'];
        return view('admin.topics.create-attachment',compact('lesson','topic'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, NotificationHelper $notify)
    {
        // dd($request->all());
        $rules=[
            'title'=>'required|max:150',
        ];
        
        
        request()->validate($rules);
        $lesson=Lesson::findOrFail($request->lesson_id);
        if(isset($request->video_type)){
            $arr=array_merge(['status'=>1],$request->only(['title','lesson_id','video_url','audio','image','text']));
        }else{
            $arr=array_merge(['status'=>1],$request->only(['title','lesson_id','video','audio','image','text']));
        }
                

        $topic=Topic::create($arr);
        if($request->attachments[0]['attachment_title']!=null){
            TopicAttachment::insert(array_map(function($a) use($topic){
                $topic_id=$topic->id;
                $title=isset($a['attachment_title']) ? $a['attachment_title'] :'N/A';
                $ext=pathinfo($a['attachment_filename'], PATHINFO_EXTENSION);
                return [
                    'topic_id'=>$topic_id,
                    'type'=>$ext,
                    'title'=>$title,
                    'attachment'=>$a['attachment_filename'],
                    'status'=>1,
                ];
            },$request->attachments));
        }
        $course=$lesson->course;
        $section_id=($course->course_details()->get()->map(function($q){
            return 
                $q->section->id;
            
        }));
    
        $users_section=User::with('student_detail')->whereHas('student_detail',function($q) use($section_id){
            $q->whereIn('section_id',$section_id);
        })->get();
        if(tenant()->plan==='large'){
            $notify->notifyMany(
                $users_section,
                'App\Topic',
                $topic->id,
                '/student/assigned-courses/'.$course->id,
                auth()->user()->name . ' has added a new topic. Title: '.$topic->title .', on Lesson: '.$lesson->title
            );
        }
        
        return redirect('/lessons/'.$lesson->id)->with('success','Data Saved.');
    }
    
    
    public function storeAttach(Request $request, FileHelper $file)
    {
        request()->validate([
            'title'=>'required|max:150',
            'attachment'=>'required',
        ]);

        $topic=Topic::findOrFail($request->topic_id);
        $ext=$request->attachment->extension() !==null ? $request->attachment->extension() : 'N/A';
        $tmp=$file->storeFile($request->attachment,'attachments');
        TopicAttachment::create(array_merge(
            ['status'=>1,'type'=>$ext,'attachment'=>$tmp], $request->only(['title','topic_id'])
        ));

        
        return redirect('/topics/'.$request->topic_id)->with('success','Data Saved.');


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
        $topic=Topic::with(['topic_attachments','lesson','lesson.course'])->findOrFail($id);

        if ( auth()->user()->hasRole('Teacher') && !empty($topic->  lesson->course->course_details) && !in_array(auth()->user()->id, $topic-> lesson->course->course_details->pluck('user_id')->toArray()) ) {
            abort(401);
        }

        $lesson=$topic['lesson'];
        $attachments=$topic['topic_attachments']->map(function($q,$sn){
            return [
                'sn'=>$sn+=1,
                'id'=>$q->id,
                'title'=>$q->title,
                'type'=>$q->type,
                'link'=>Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/attachments/'.$q->attachment),
                'status'=>$q->status == 1 ? 11: 10,
                'created_at'=>$q->created_at
            ];
        });
        $video=$this->formatVideoData($topic);

        $video=$video['video']==null && $video['videoType'] == null ? null:  $video;
        $resources=[
            'video'=> $video,
            'audio'=>$topic->audio ? Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/audios/'.$topic->audio) : null,
            'image'=>$topic->image ? Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/images/'.$topic->image): null,
            'text'=>$topic->text ? $topic->text : null,
        ];
        // dd($resources);
        return view('admin.topics.show',compact('topic','lesson','attachments','resources'));
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
        $topic=Topic::with(['topic_attachments','lesson'])->findOrFail($id);

        if ( auth()->user()->hasRole('Teacher') && !empty($topic->lesson->course->course_details) && !in_array(auth()->user()->id, $topic-> lesson->course->course_details->pluck('user_id')->toArray()) ) {
            abort(401);
        }

        $lesson=$topic['lesson'];
        return view('admin.topics.edit',compact('topic','lesson'));
        // dd($topic);
    }

    public function editAttach($id)
    {
        //
        $attachment=TopicAttachment::with(['topic','topic.lesson','topic.lesson.course'])->findOrFail($id);
        $topic=$attachment['topic'];

        if ( auth()->user()->hasRole('Teacher') && !empty($topic->lesson->course->course_details) && !in_array(auth()->user()->id, $topic-> lesson->course->course_details->pluck('user_id')->toArray()) ) {
            abort(401);
        }

        $lesson=$topic['lesson'];
        return view('admin.topics.edit-attachment',compact('topic','lesson','attachment'));
        // dd($topic);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id,FileHelper $file)
    {
        //
        $topic=Topic::findOrFail($id);
        $rules=[
            'title'=>'required|max:150',
        ];
        
        // if(isset($request->video_type))
        // {
        //     $video_type='url';
        //     array_merge($rules,['video_url'=>'required']);
        // }else{
        //     array_merge($rules,['video'=>'required']);
        // }

        request()->validate($rules);
        $lesson=Lesson::findOrFail($topic->lesson_id);
        
        $arr=$request->only(['title']);
        if(isset($request->references[0])){
            if($request->references[0]['references']!=null){
                $arr=array_merge(['reference_links'=>json_encode(Arr::flatten($request->references))],$arr);
            }
        }else{
            $arr=array_merge(['reference_links'=>null]);
        }

        $topic->update($arr);


        return redirect('/lessons/'.$lesson->id)->with('success','Data updated.');
    }

    public function updateAttach(Request $request, $id,FileHelper $file)
    {
        request()->validate([
            'title'=>'required|max:150'
        ]);
        $arr=$request->only(['title']);
        $attachment=TopicAttachment::findOrFail($id);
        if($request->attachment){   
            $ext=$request->attachment->extension() !==null ? $request->attachment->extension() : 'N/A';
            $tmp=$file->updateFile($request->attachment,'attachments',$attachment->attachment);
            $arr=array_merge($arr,['attachment'=>$tmp,'type'=>$ext]);
        }

        $attachment->update($arr);

        return redirect('/topics/'.$attachment->topic_id)->with('success','Data updated.');

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

    // $status -> send 1 to active, 0 to deactivate
    public function statusControl($id,$status)
    {
        if(!in_array($status,[0,1])){
            return back()->with('error','Action cannot be recognized. Please try again.');
        }
        $message=$status==1 ? 'Attachemnt activated.': 'Attachemnt deactivated' ;
        $topic=Topic::findOrFail($id);

        $topic->changeStatus($status);

        return back()->with('success',$message);
    }

    // $status -> send 1 to active, 0 to deactivate
    public function statusControlBulk(Request $request,$status)
    {
        if(!in_array($status,[0,1]) || !json_decode($request->list)){
            return back()->with('error','Action cannot be recognized. Please try again.');
        }

        $message=$status==1 ? 'Attachment(s) activated.': 'Attachment(s) deactivated' ;
        $topics = Topic::whereIn('id',json_decode($request->list))->get()->map(function($q) use($status){
            $q->changeStatus($status); 
        });

        return back()->with('success',$message);
    }


    // $status -> send 1 to active, 0 to deactivate
    public function statusControlAttach($id,$status)
    {
        if(!in_array($status,[0,1])){
            return back()->with('error','Action cannot be recognized. Please try again.');
        }
        $message=$status==1 ? 'Attachemnt activated.': 'Attachemnt deactivated.' ;
        $topic=TopicAttachment::findOrFail($id);

        $topic->changeStatus($status);

        return back()->with('success',$message);
    }

    // $status -> send 1 to active, 0 to deactivate
    public function statusControlBulkAttach(Request $request,$status)
    {
        if(!in_array($status,[0,1]) || !json_decode($request->list)){
            return back()->with('error','Action cannot be recognized. Please try again.');
        }

        $message=$status==1 ? 'Attachment(s) activated.': 'Attachment(s) deactivated' ;
        $topics = TopicAttachment::whereIn('id',json_decode($request->list))->get()->map(function($q) use($status){
            $q->changeStatus($status); 
        });

        return back()->with('success',$message);
    }

    private function formatVideoData($topic)
    {
        if (!empty($topic->video)) {
            $data['video'] = Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/videos/'. $topic->video);
            $data['videoType'] = 'video';
        }
        else if (!empty($topic->video_url)) {
            $url = $topic->video_url;
            $data['video'] = preg_replace("/\s*[a-zA-Z\/\/:\.]*youtube.com\/watch\?v=([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i","//www.youtube.com/embed/$1", $url);
            $data['videoType'] = 'url';
        }
        else {
            $data['video'] = null;
            $data['videoType'] = null;
        }

        return $data;
    }
    
    public function storeFile(Request $request)
    {
        $getFile = $_FILES['selectedFile'];

        if ( isset($getFile) && !empty($getFile) ) {
            switch ($request->type) {
                case 'video':
                   $folder = 'videos';
                    break;
                    
                case 'audio':
                   $folder = 'audios';
                    break;
                    
                case 'image':
                   $folder = 'images';
                    break;
                
                default:
                   $folder = 'attachments';
                    break;
            }

            $info = pathinfo($getFile['name']);
            $fileName = $folder . '-'. Str::uuid() . '.' . $info['extension'];

            Storage::disk(config('app.storage_driver'))->putFileAs(
                $folder, $getFile['tmp_name'], $fileName
            );
        }
        else {
            return response()->json(['result' => 'failure']);
        }

        return response()->json(['result' => 'success', 'filename' => $fileName]);
    }

    public function removeFile($folder, $filename, FileHelper $file)
    {
        return $file->deleteFile($folder, $filename);
    }
}
