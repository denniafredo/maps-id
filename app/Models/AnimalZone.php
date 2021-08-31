<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnimalZone extends Model
{
    protected $table = 'animal_zones';
    protected $fillable = [
        'name'
    ];
}
