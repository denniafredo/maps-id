<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KML extends Model
{
    protected $table = 'kmls';

    protected $fillable = ['path'];
}
