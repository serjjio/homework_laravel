<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $table = 'building';

    public function street()
    {
        return $this->belongsTo(Street::class);
    }
}
