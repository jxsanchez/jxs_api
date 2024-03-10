<?php

namespace App\Models\Pokemon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Pokemon\Type;

class Pokemon extends Model
{
    use HasFactory;

    protected $table = 'pokemons';

    protected $fillable = [
        'generation_id',
        'national_pokedex_number',
        'type_1_id',
        'type_2_id',
        'name',
        'description',
        'official_artwork_url',
        'icon_url'
    ];

    protected $visible = [
        'id',
        'generation_id',
        'national_pokedex_number',
        'type_1_id',
        'type_2_id',
        'name',
        'description',
        'official_artwork_url',
        'icon_url',
        'created_at',
        'updated_at'
    ];

    public function type_1()
    {
        return $this->hasOne(Type::class, 'id', 'type_1_id');
    }

    public function type_2()
    {
        return $this->hasOne(Type::class, 'id', 'type_2_id');
    }
}
