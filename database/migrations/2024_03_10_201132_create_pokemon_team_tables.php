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
        Schema::create('pokemon_teams', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('user_pokemons', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('team_id')->unsigned();
            $table->bigInteger('pokemon_id')->unsigned();
            $table->timestamps();

            $table->foreign('team_id')->references('id')->on('pokemon_teams');
            $table->foreign('pokemon_id')->references('id')->on('pokemons');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pokemon_teams', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('user_pokemons', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            $table->dropForeign(['pokemon_id']);
        });

        Schema::dropIfExists('pokemon_teams');
        Schema::dropIfExists('user_pokemons');
    }
};
