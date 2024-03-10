<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pokemon_generations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->timestamps();
        });

        Schema::create('pokemon_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('color')->nullable();
            $table->timestamps();
        });

        Schema::create('pokemons', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('generation_id')->unsigned()->nullable();
            $table->smallInteger('national_pokedex_number')->unsigned()->nullable();
            $table->bigInteger('type_1_id')->unsigned()->nullable();
            $table->bigInteger('type_2_id')->unsigned()->nullable();
            $table->string('name')->nullable();
            $table->string('description', 1024)->nullable();
            $table->string('official_artwork_url', 512)->nullable();
            $table->string('icon_url', 512)->nullable();
            $table->timestamps();

            $table->foreign('generation_id')->references('id')->on('pokemon_generations');
            $table->foreign('type_1_id')->references('id')->on('pokemon_types');
            $table->foreign('type_2_id')->references('id')->on('pokemon_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pokemons', function (Blueprint $table) {
            $table->dropForeign(['generation_id']);
            $table->dropForeign(['type_1_id']);
            $table->dropForeign(['type_2_id']);
        });

        Schema::dropIfExists('pokemon_types');
        Schema::dropIfExists('pokemon_generations');
        Schema::dropIfExists('pokemons');
    }
};
