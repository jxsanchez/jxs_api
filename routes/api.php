<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Pokemon\PokemonTeamController;
use App\Http\Controllers\Pokemon\PokemonController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/***************
 * AUTH ROUTES *
 ***************/
Route::post('/login', [ AuthController::class, 'login' ]);
Route::post('/signup', [ AuthController::class, 'signup' ]);
Route::middleware('auth:sanctum')->post('/logout', [ AuthController::class, 'logout' ]);

/******************
 * POKÉAPI ROUTES *
 ******************/
Route::prefix('pokemon')->group(function () {
    Route::get('/', [ PokemonController::class, 'index' ]);
    Route::get('/{pokemon}', [ PokemonController::class, 'show' ]);
});

Route::prefix('pokemon_teams')->middleware('auth:sanctum')->group(function () {
    Route::get('/{pokemon_team}', [ PokemonTeamController::class, 'show' ]);
    Route::post('/store', [ PokemonTeamController::class, 'store' ]);
});
