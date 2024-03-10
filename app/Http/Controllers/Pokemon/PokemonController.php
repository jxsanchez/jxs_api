<?php

namespace App\Http\Controllers\Pokemon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Pokemon\Pokemon;

class PokemonController extends Controller
{
    public function index()
    {
        return response([ 
            'pokemon' => Pokemon::orderBy('national_pokedex_number', 'ASC')->get()
        ], 200);
    }

    public function show(Pokemon $pokemon)
    {
        if (! $pokemon) {
            return response('Bad Pokemon id', 404);
        }

        return response([ 
            'pokemon' => $pokemon
        ], 200);
    }

    public function search(Request $request)
    {

    }
}
