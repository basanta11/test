<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestQuestion extends Model
{
    //
    protected $fillable=['test_set_id','title','title','type','note','order','marks','status'];
    
    public function delete()
    {
        foreach($this->test_question_options()->get() as $data)
        {
            $data->delete();
        }
        foreach($this->attachments()->get() as $data)
        {
            $data->delete();
        }
        parent::delete();
    }
    public function test_question_options()
    {
        return $this->hasMany(TestQuestionOption::class);
    }

    public function test_set()
    {
        return $this->belongsTo(TestSet::class);
    }
    
    public function attachments()
    {
        return $this->morphMany('App\Attachment', 'attachmentable');
    }
    
}
