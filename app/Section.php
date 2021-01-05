<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable =  ['classroom_id', 'title', 'status', 'user_id'];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function course_details()
    {
        return $this->hasMany(CourseDetail::class);
    }

    public function exams(){
        return $this->belongsToMany(Exam::class,'exam_section');
    } 

    public function class_teacher()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function student_details()
    {
        return $this->hasMany(StudentDetail::class);
    }

    public function section_behaviours()
    {
        return $this->hasMany(SectionBehaviour::class);
    }
}
