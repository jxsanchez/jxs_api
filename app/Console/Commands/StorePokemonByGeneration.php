<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Pokemon\Generation;
use App\Models\Pokemon\Pokemon;
use App\Models\Pokemon\Type;

use Exception;
use Log;

class StorePokemonByGeneration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pokemon:store-by-generation {generationId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store all Pokemon from a generation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $generationId = $this->argument('generationId');
        
        $maxGenerationId = Generation::find(\DB::table('pokemon_generations')->max('id'))?->id;
        if (! Generation::find($generationId)) {
            $this->error("Invalid generation, please enter a generation between 1 and {$maxGenerationId}");
            return;
        }
        
        try {
            $api = new \PokePHP\PokeApi;

            // Get generation info, including list of all Pokemon
            $generationResponse = json_decode($api->gameGeneration($generationId), true);
            if (isset($generationResponse['pokemon_species'])) {
                // Get info for each Pokemon in a generation
                foreach ($generationResponse['pokemon_species'] as $generationPokemon) {
                    if (isset($generationPokemon['name'])) {
                        // Contains Pokemon type and artwork data
                        $pokemonResponse = json_decode($api->pokemon($generationPokemon['name']), true);
                        // Contains Pokemon order and description data
                        $pokemonSpeciesResponse = json_decode($api->pokemonSpecies($generationPokemon['name']), true);

                        [ $type1Id, $type2Id ] = $this->getPokemonTypes($pokemonResponse);
                        $pokemonData = [
                            'generation_id' => $generationId,
                            'national_pokedex_number' => $this->getNationalPokedexNumber($pokemonSpeciesResponse),
                            'type_1_id' => $type1Id,
                            'type_2_id' => $type2Id,
                            'name' => $generationPokemon['name'],
                            'description' => $this->getPokemonDescription($pokemonSpeciesResponse),
                            'official_artwork_url' => $this->getPokemonOfficialArtworkUrl($pokemonResponse),
                            'icon_url' => $this->getPokemonIconUrl($pokemonResponse)
                        ];

                        Pokemon::create($pokemonData);

                        $this->info("#{$pokemonData['national_pokedex_number']} {$pokemonData['name']} was saved");
                    }
                }
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            $this->error($e->getMessage());
        }
    }

    public function getNationalPokedexNumber($response)
    {
        return (
            isset($response['pokedex_numbers']) &&
            isset($response['pokedex_numbers'][0]) &&
            isset($response['pokedex_numbers'][0]['pokedex']) &&
            isset($response['pokedex_numbers'][0]['pokedex']['name']) &&
            $response['pokedex_numbers'][0]['pokedex']['name'] === 'national'
        ) 
        ? $response['pokedex_numbers'][0]['entry_number'] 
        : null;
    }

    public function getPokemonTypes($response)
    {
        $types = [];
        if (isset($response['types'])) {
            // Pokemon can have up to 2 types
            foreach ($response['types'] as $type) {
                if (isset($type['type']) && isset($type['type']['name'])) {
                    $types[] = Type::where('name', $type['type']['name'])->first()?->id;
                }
            }
        }

        return [ ($types[0] ?? null), ($types[1] ?? null) ];
    }

    public function getPokemonDescription($response)
    {
        $description = null;

        if (isset($response['flavor_text_entries'])) {
            // Get English entries
            $entries = array_filter($response['flavor_text_entries'], function ($entry) {
                return (
                    isset($entry['language']) && 
                    isset($entry['language']['name']) && 
                    $entry['language']['name'] === 'en'
                );
            });

            // PokeAPI stores entries in order of game release, so we will get the last entry
            if ($latestEntry = end($entries)) {
                $description = $latestEntry['flavor_text'] ?? null;
            }
        }

        return $description;
    }

    public function getPokemonOfficialArtworkUrl($response)
    {
        return (
            isset($response['sprites']) &&
            isset($response['sprites']['other']) &&
            isset($response['sprites']['other']['official-artwork'])
        ) 
        ? $response['sprites']['other']['official-artwork']['front_default'] 
        : null;
    }

    public function getPokemonIconUrl($response)
    {
        return isset($response['sprites'])
            ? $response['sprites']['front_default']
            : null;
    }
}
