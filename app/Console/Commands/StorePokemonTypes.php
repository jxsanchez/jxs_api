<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Pokemon\Type;

use Log;

class StorePokemonTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pokemon:store-types';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store all Pokemon types';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Each type has an associated color 
        // https://gist.github.com/apaleslimghost/0d25ec801ca4fc43317bcff298af43c3
        $typeColorMap = [
            'normal' => '#A8A77A',
            'fire' => '#EE8130',
            'water' => '#6390F0',
            'electric' => '#F7D02C',
            'grass' => '#7AC74C',
            'ice' => '#96D9D6',
            'fighting' => '#C22E28',
            'poison' => '#A33EA1',
            'ground' => '#E2BF65',
            'flying' => '#A98FF3',
            'psychic' => '#F95587',
            'bug' => '#A6B91A',
            'rock' => '#B6A136',
            'ghost' => '#735797',
            'dragon' => '#6F35FC',
            'dark' => '#705746',
            'steel' => '#B7B7CE',
            'fairy' => '#D685AD'
        ];

        try {
            $api = new \PokePHP\PokeApi;
            
            foreach ($typeColorMap as $type => $color) {
                $response = json_decode($api->pokemonType($type), true);
                if (isset($response['name'])) {
                    Type::create([
                        'name' => $response['name'],
                        'color' => $color
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $this->error($e->getMessage());
        }
    }
}
