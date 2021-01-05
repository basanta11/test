<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Set extends Model
{
    protected $fillable = ['title', 'exam_id'];

    public function delete()
    {
        foreach($this->questions()->get() as $data)
        {
            $data->delete();
        }
        parent::delete();
    }
    
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function set_users()
    {
        return $this->hasMany(SetUser::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
