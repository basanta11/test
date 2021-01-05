<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $fillable = [ 'title', 'description', 'status' ];

    public function sections()
    {
        return $this->hasMany(Section::class);
    }
}
