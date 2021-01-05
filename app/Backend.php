<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Backend extends Model
{
    //
    protected $fillable=[
        'user_id',
        'theme'
    ];

    public function principle()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
