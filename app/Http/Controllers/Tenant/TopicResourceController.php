<?php

namespace App\Http\Controllers\Tenant;

use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TopicResourceController extends Controller
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
    public function create($id,$type)
    {
        //
        $rule=['audio','video','text','image'];
        if(!in_array($type,$rule))
            return back()->with('error','Action unrecognized. Please try again.');

        $topic=Topic::with(['lesson','lesson.course'])->findOrFail($id);
        $lesson=$topic['lesson'];
        return view('admin.topics.create-resources',compact('lesson','topic','type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$id,FileHelper $file)
    {
        //
        $rule=['audio','video','text','image'];
        if(!in_array($request->type,$rule))
            return back()->with('error','Action unrecognized. Please try again.');
        
        $topic=Topic::with(['lesson','lesson.course'])->findOrFail($id);
        $arr=array();

        if(isset($request->video_type) && $request->video_url){
            
            $arr=array_merge($arr,['video_url'=>$request->video_url]);
        }elseif($request->video){
            $video=$file->storeFile($request->video,'videos',$topic->video);
            $arr=array_merge($arr,['video'=>$video]);
        }
        if($request->audio){
            $audio=$file->storeFile($request->audio,'audios',$topic->audio);
            $arr=array_merge($arr,['audio'=>$audio]);
        }
        if($request->image){
            $image=$file->storeFile($request->image,'images',$topic->image);
            $arr=array_merge($arr,['image'=>$image]);
        }
        if($request->text){
            $arr=array_merge($arr,['text'=>$request->text]);
        }
        if(!empty($arr))
            $topic->update($arr);
        
        return redirect('/topics/'.$topic->id)->with('success','Data saved.');
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
    public function edit($id,$type)
    {
        //
        $rule=['audio','video','text','image'];
        if(!in_array($type,$rule))
            return back()->with('error','Action unrecognized. Please try again.');

        $topic=Topic::with(['lesson','lesson.course'])->findOrFail($id);

        if ( auth()->user()->hasRole('Teacher') && !empty($topic->lesson->course->course_details) && !in_array(auth()->user()->id, $topic-> lesson->course->course_details->pluck('user_id')->toArray()) ) {
            abort(401);
        }

        $videoData = $this->formatVideoData($topic);
        $lesson=$topic['lesson'];
        return view('admin.topics.edit-resources',compact('lesson','topic','type','videoData'));
        
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
        $rule=['audio','video','text','image'];
        if(!in_array($request->type,$rule))
            return back()->with('error','Action unrecognized. Please try again.');
        
        $topic=Topic::with(['lesson','lesson.course'])->findOrFail($id);
        $arr=array();

        if(isset($request->video_type) && $request->video_url){
            if($topic->video){
                $file->deleteFile('videos',$topic->video);
                $arr=array_merge($arr,['video'=>null]);
            }
            $arr=array_merge($arr,['video_url'=>$request->video_url]);
        }elseif($request->video){
            if($topic->video_url){
                $arr=array_merge($arr,['video_url'=>null]);
            }
            $video=$file->updateFile($request->video,'videos',$topic->video);
            $arr=array_merge($arr,['video'=>$video]);
        }
        if($request->audio){
            $audio=$file->updateFile($request->audio,'audios',$topic->audio);
            $arr=array_merge($arr,['audio'=>$audio]);
        }
        if($request->image){
            $image=$file->updateFile($request->image,'images',$topic->image);
            $arr=array_merge($arr,['image'=>$image]);
        }
        if($request->text){
            $arr=array_merge($arr,['text'=>$request->text]);
        }
        if(!empty($arr))
            $topic->update($arr);
        
        return redirect('/topics/'.$topic->id)->with('success','Data updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id, FileHelper $file)
    {
        //
        $rule=['audio','video','text','image'];
        if(!in_array($request->type,$rule))
            return back()->with('error','Action unrecognized. Please try again.');
            
        $topic=Topic::with(['lesson','lesson.course'])->findOrFail($id);
        $arr=array();

        if($request->type=="text"){
            $arr=array_merge($arr,['text'=>null]);
        }
        if($request->type=="audio"){
            $arr=array_merge($arr,['audio'=>null]);
            $file->deleteFile('audio',$topic->audio);
        }
        if($request->type=="image"){
            $arr=array_merge($arr,['image'=>null]);
            $file->deleteFile('images',$topic->image);
        }
        if($request->type=="video"){
            $arr=array_merge($arr,['video'=>null,'video_url'=>null]);
            $file->deleteFile('videos',$topic->video);
        }
        if(!empty($arr)){
            $topic->update($arr);
            return redirect('/topics/'.$topic->id)->with('success','Data deleted.');
        }else{
            return redirect('/topics/'.$topic->id)->with('error','No actions performed. Please try again');
        }

        
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
}
