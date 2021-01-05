<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    //
    protected $fillable=['id','title','status','teacher_id','course_id','start_date','end_date','start_time','end_time','token'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function meeting_details()
    {
        return $this->hasMany(MeetingDetail::class);
    }
    public function teacher()
    {
        return $this->belongsTo(User::class,'teacher_id');
    }
    public function videos()
    {
        return $this->hasMany(MeetingVideo::class);
    }
}
