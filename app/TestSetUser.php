<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestSetUser extends Model
{
    //
    protected $table = 'test_set_user';
    
    protected $fillable = ['test_set_id', 'user_id', 'is_finished', 'teacher_checking'];

    public function test_set()
    {
        return $this->belongsTo(TestSet::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function test_answers()
    {
        return $this->hasMany(TestAnswer::class);
    }
}
