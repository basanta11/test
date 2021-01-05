<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MeetingDetail extends Model
{
    //
    protected $fillabke=['meeting_id','section_id','status'];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
