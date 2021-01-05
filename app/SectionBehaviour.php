<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SectionBehaviour extends Model
{
    //
    protected $table='section_behaviour';

    protected $fillable=['section_id','behaviour_type_id','status'];

    public function behaviour_type()
    {
        return $this->belongsTo(BehaviourType::class);
    }
}
