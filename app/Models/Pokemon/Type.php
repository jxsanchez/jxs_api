<?php

namespace App\Models\Pokemon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $table = 'pokemon_types';

    protected $fillable = [
        'name',
        'color'
    ];

    protected $visible = [
        'id',
        'name',
        'color',
        'created_at',
        'updated_at'
    ];
}
