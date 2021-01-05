<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Frontend extends Model
{
    //
    protected $fillable=[
        'user_id',
        'banners',
        'primary_color',
        'secondary_color',
        'mission',
        'mission_image',
        'vision',
        'vision_image',
        'goal',
        'goal_image',
        'about_us',
        'social_links',
        'contacts',
        'card_color',
        'map'
    ];

    public function principle()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
