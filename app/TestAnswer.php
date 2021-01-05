<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestAnswer extends Model
{
    //

    protected $fillable = ['test_set_user_id', 'test_question_id', 'test_question_option_id', 'answer'];
}
