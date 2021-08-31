<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnimalVideo extends Model
{
    use SoftDeletes;
    
    protected $table = 'animal_videos';
    protected $fillable = [
        'animal_id',
        'path',
        'filename',
        'contributor'
    ];
}
