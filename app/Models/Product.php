<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //

    protected $fillable = [
        'id',
        'name',
        'scientific_name',
        'description',
        'type',
        'weight_per_unit_g',
        'created_at',
        'updated_at',
    ];
}
