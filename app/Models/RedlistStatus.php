<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RedlistStatus extends Model
{
    protected $table = 'redlist_statuses';
    protected $fillable = [
        'name',
        'code',
        'image'
    ];
}
