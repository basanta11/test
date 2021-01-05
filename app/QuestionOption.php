<?php

namespace App;

use App\Helpers\FileHelper;
use Illuminate\Database\Eloquent\Model;

class QuestionOption extends Model
{
    protected $fillable = ['question_id', 'title', 'type', 'is_correct'];

    public function delete()
    {

        $file=new FileHelper();
        if($this->type==1){
            $file->deleteFile('question_options',$this->title);
        }
        parent::delete();

    }
}
