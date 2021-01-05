<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    //
    protected $fillable=[
        'title','lesson_id','status','video','video_url','reference_links','image','audio','text'
    ];
    public function topic_attachments()
    {
        return $this->hasMany(TopicAttachment::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    
    public function changeStatus($status)
    {
        $this->update(['status'=>$status]);
        
        $this->topic_attachments()->get()->map(function($q) use($status){
            return $q->changeStatus($status);
        });
    }
}
