<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnimalImage extends Model
{
    use SoftDeletes;
    
    protected $table = 'animal_images';
    protected $fillable = [
        'animal_id',
        'path',
        'filename',
        'contributor',
        'is_default'
    ];
}
