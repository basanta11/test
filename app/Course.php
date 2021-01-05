<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    //
    protected $fillable=['title','credit_hours','learn_what','status', 'classroom_id','description','image'];

    public function course_details(){
        return $this->hasMany(CourseDetail::class);
    }

    public function classroom(){
        return $this->belongsTo(Classroom::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
}
