<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Pokemon\Generation;

use Log;

class StorePokemonGenerations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pokemon:store-generations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store all pokemon generations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $api = new \PokePHP\PokeApi;

            // Get ALL generations
            $response = json_decode($api->gameGeneration(""), true);
            if (isset($response['count']) && $response['count'] > 0) {
                if (isset($response['results'])) {
                    foreach ($response['results'] as $generation) {
                        Generation::create($generation);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $this->error($e->getMessage());
        }
    }
}
