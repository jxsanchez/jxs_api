<?php

namespace App\Http\Controllers\Pokemon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Pokemon\PokemonTeam;

use Exception;

class PokemonTeamController extends Controller
{
    public function store(Request $request)
    {
        if (! $user = $request->user()) {
            return response('Bad user id', 404);
        }

        try {
            if ( !$pokemonTeam = $user->pokemon_team ) {
                $pokemonTeam = PokemonTeam::create([
                    'user_id' => $user->id
                ]);
            }

            return response([
                'pokemon_team' => $pokemonTeam 
            ], 200);
        } catch (Exception $e) {
            return response([ 'message' => $e->getMessage() ], 400);
        }
    }

    public function show(PokemonTeam $pokemonTeam)
    {
        if (! $pokemonTeam) {
            return response('Bad Pokemon Team id', 404);
        }

        return response([ 
            'pokemon_team' => $pokemonTeam
        ], 200);
    }
}
