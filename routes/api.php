<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\RubricController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::apiResource('companies', CompanyController::class)->only([
    'index', 'show'
]);

Route::apiResource('buildings', BuildingController::class)->only([
    'index', 'show'
]);

Route::apiResource('rubrics', RubricController::class)->only([
    'index', 'show'
]);

Route::fallback(function(){
    return response()->json(
        ['error' =>
            ['route' => 'Not Found']
        ],
        404
    );
});
