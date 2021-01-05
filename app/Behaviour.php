<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Behaviour extends Model
{
    protected $fillable = ['student_id', 'teacher_id', 'behaviour', 'marks'];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
