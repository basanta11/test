<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HomeworkSection extends Model
{
    //

    protected $table = 'homework_section';
    protected $fillable=[
        'section_id', 'homework_id'
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
