<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BehaviourType extends Model
{
    //
    protected $table='behavior_types';
    
    protected $fillable=[ 'title' , 'status' ];
}
