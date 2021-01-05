<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $connection = 'mysql';

    protected $fillable = [ 'title', 'description' ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
