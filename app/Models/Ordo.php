<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ordo extends Model
{
    protected $table = 'ordos';
    protected $fillable = [
        'name'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
