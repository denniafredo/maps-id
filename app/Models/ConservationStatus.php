<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConservationStatus extends Model
{
    protected $table = 'conservation_statuses';
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
