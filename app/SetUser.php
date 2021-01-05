<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SetUser extends Model
{
    protected $table = 'set_user';
    
    protected $fillable = ['set_id', 'user_id', 'is_finished', 'teacher_checking'];

    public function set()
    {
        return $this->belongsTo(Set::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
