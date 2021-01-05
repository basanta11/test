<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    //
    protected $fillable=[
        'course_id','title','brief','status'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function topics()
    {
        return $this->hasMany(Topic::class);
        
    }

    public function changeStatus($status)
    {
        $this->update(['status'=>$status]);
        
        $this->topics()->get()->map(function($q) use($status){
            return $q->changeStatus($status);
        });
    }
}
