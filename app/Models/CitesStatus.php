<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CitesStatus extends Model
{
    protected $table = 'cites_statuses';
    protected $fillable = [
        'name'
    ];
}
