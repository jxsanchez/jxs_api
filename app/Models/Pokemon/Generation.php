<?php

namespace App\Models\Pokemon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Generation extends Model
{
    use HasFactory;

    protected $table = 'pokemon_generations';

    protected $fillable = [
        'name'
    ];

    protected $visible = [
        'id',
        'name',
        'created_at',
        'updated_at'
    ];
}
