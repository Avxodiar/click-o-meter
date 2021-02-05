<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Click extends Model
{
    protected $table = 'clicks';

    protected $fillable = ['time', 'width', 'x', 'y'];

    public $timestamps = false;

    public function link()
    {
        return $this->belongsTo(Site::class);
    }
}
