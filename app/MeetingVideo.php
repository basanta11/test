<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MeetingVideo extends Model
{
    //
    protected $fillable=[
        'meeting_id',
        'video',
    ];
}
