<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeightUnit extends Model
{
    protected $table = 'weight_units';
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
