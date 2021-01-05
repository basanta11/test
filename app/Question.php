<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['set_id', 'title', 'type', 'note', 'order', 'marks', 'status'];

    public function delete()
    {
        foreach($this->question_options()->get() as $data)
        {
            $data->delete();
        }
        foreach($this->attachments()->get() as $data)
        {
            $data->delete();
        }
        parent::delete();
    }
    public function question_options()
    {
        return $this->hasMany(QuestionOption::class);
    }

    public function set()
    {
        return $this->belongsTo(Set::class);
    }
    
    public function attachments()
    {
        return $this->morphMany('App\Attachment', 'attachmentable');
    }
}
