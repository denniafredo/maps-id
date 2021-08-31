<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kingdom extends Model
{
    protected $table = 'kingdoms';
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
