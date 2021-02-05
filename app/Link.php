<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $table = 'links';

    protected $fillable = ['link'];

    public $timestamps = false;

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function clicks()
    {
        return $this->hasMany(Click::class);
    }
}
