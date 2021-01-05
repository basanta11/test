<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestSet extends Model
{
    //
    protected $fillable=['title','test_id'];
    
    public function delete()
    {
        foreach($this->testQuestions()->get() as $data)
        {
            $data->delete();
        }
        parent::delete();
    }
    
    public function testQuestions()
    {
        return $this->hasMany(TestQuestion::class);
    }

    public function test()
    {
        return $this->belongsTo(Test::class);
    }
}
