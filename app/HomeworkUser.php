<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HomeworkUser extends Model
{
    //
    protected $table = 'homework_user';
    protected $fillable=[
        'user_id', 'homework_id' ,'obtained_marks' ,'answer','status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attachments()
    {
        return $this->morphMany('App\Attachment', 'attachmentable');
    }

    public function homework()
    {
        return $this->belongsTo(Homework::class);
    }

}
