<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TopicAttachment extends Model
{
    //
    protected $fillable=[
        'topic_id','title','attachment','type','status'
    ];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function changeStatus($status)
    {
        $this->update(['status'=>$status]);
    }
}
