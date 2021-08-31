<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Note: Kalau pake nama model 'Class' error
// Fix: Class -> ClassModel
class ClassModel extends Model
{
    protected $table = 'classes';
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
