<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genus extends Model
{
    protected $table = 'genuses';
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
