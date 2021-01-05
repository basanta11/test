<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentReading extends Model
{
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}
