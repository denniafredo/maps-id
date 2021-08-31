<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Animal extends Model
{
    use SoftDeletes;

    protected $table = 'animals';
    protected $fillable = [
        'scientific_name',
        'local_name',
        'label_name',
        'body_height',
        'body_length_1',
        'body_length_2',
        'body_tail',
        'body_weight',
        'description',
        'habitat',
        'weight_unit_id',
        'conservation_id',
        'cites_id',
        'redlist_id',
        'kingdom_id',
        'phylum_id',
        'class_id',
        'ordo_id',
        'family_id',
        'genus_id'
    ];
}
