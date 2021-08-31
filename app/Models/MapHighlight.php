<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MapHighlight extends Model
{
    use SoftDeletes;
    
    protected $table = 'map_highlights';
    protected $fillable = [
        'label',
        'latitude',
        'longitude',
        'is_active'
    ];
}
