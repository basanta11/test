<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = ['set_user_id', 'question_id', 'question_option_id', 'answer'];
}
