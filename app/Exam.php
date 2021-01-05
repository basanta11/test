<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = ['title', 'exam_start', 'duration', 'type', 'full_marks', 'pass_marks', 'status', 'user_id', 'course_id','show_result'];

    public function sections()
    {
        return $this->belongsToMany(Section::class,'exam_section');
    }

    public function sets()
    {
        return $this->hasMany(Set::class);
    }
    
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
