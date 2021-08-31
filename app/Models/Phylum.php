<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Phylum extends Model
{
    protected $table = 'phylums';
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
