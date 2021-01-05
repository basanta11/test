<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    //
    protected $fillable=[
      'id','type' ,'notifiable_type', 'notifiable_id' ,'data'
    ];
}
