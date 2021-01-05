<?php

namespace App;

use App\Helpers\FileHelper;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    //
    protected $fillable=['body','attachmentable_id','attachmentable_type'];
    
    
    public function delete()
    {
        $file=new FileHelper();
        $file->deleteFile('attachments',$this->body);
        parent::delete();
    }
    
    public function attachmentable()
    {
        return $this->morphTo();
    }
}
