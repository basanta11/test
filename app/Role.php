<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $connection = 'mysql';

    protected $fillable = [ 'title', 'status' ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
}
