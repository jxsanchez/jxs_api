<?php

namespace App\Models\Pokemon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PokemonTeam extends Model
{
    use HasFactory;

    protected $table = 'pokemon_teams';

    protected $fillable = [
        'user_id',
    ];

    protected $visible = [
        'id',
        'user_id',
        'created_at',
        'updated_at'
    ];
}
