<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSectionBehaviour extends Model
{
    //
    protected $table='user_section_behaviour';

    protected $fillable=[
        'user_id','section_behaviour_id','marks','status','teacher_id'
    ];

    public function section_behaviour()
    {
        return $this->belongsTo(SectionBehaviour::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
