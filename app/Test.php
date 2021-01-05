<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    //
    protected $fillable=['lesson_id','title' ,'test_start','duration','type','full_marks','pass_marks', 'status','created_by','show_result'];
    public function delete()
    {
        foreach($this->testSets()->get() as $data){
            $data->delete();
        }
        parent::delete();
    }
    public function teacher()
    {
        return $this->belongsTo(User::class,'created_by');
    }
    
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function testSets()
    {
        return $this->hasMany(TestSet::class);
    }
}   
