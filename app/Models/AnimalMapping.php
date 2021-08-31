<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnimalMapping extends Model
{
    use SoftDeletes;
    
    protected $table = 'animal_mappings';
    protected $fillable = [
        'animal_id',
        'province_id',
        'latitude',
        'longitude'
    ];
}
