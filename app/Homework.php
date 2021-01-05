<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Homework extends Model
{
    //
    protected $table="homeworks";
    protected $fillable=[
        'course_id', 'title','question','full_marks','pass_marks','due_date_time' ,'created_by','status'
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function delete()
    {
        foreach($this->attachments()->get() as $data)
        {
            $data->delete();
        }
        parent::delete();
    }



    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function homework_section()
    {
        return $this->hasMany(HomeworkSection::class);
    }

    public function homework_questions()
    {
        return $this->hasMany(HomeworkQuestion::class);
    }
    public function attachments()
    {
        return $this->morphMany('App\Attachment', 'attachmentable');
    }

    public function homework_users()
    {
        return $this->hasMany(HomeworkUser::class);
    }
}
