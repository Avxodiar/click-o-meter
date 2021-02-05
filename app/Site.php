<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $table = 'sites';

    protected $fillable = ['name'];

    public $timestamps = false;

    public function links()
    {
        return $this->hasMany(Link::class);
    }
}
