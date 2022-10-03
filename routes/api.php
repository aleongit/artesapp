<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//controllers
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProducteController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ComandaController;
use App\Http\Controllers\DashboardController;

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

/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/

//passport auth
Route::group(['prefix' => 'auth'], function() {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('profile',[AuthController::class, 'profile']);
        Route::post('update-profile',[AuthController::class, 'update_profile']);
        Route::post('update-password',[AuthController::class, 'update_password']);
    });
});

//middleware auth api
Route::group(['middleware' => 'auth:api'], function() {
    
    //tots els USERS (admin i user)
    Route::resource('clients', ClientController::class);
    Route::get('get-tipus',[ClientController::class, 'tipus']);

    Route::resource('comandes', ComandaController::class)
        ->parameters(['comandes' => 'comanda']);
    Route::get('get-estats',[ComandaController::class, 'estats']);
    Route::get('get-clients', [ComandaController::class, 'clients']);
    Route::get('get-categories-productes', [ComandaController::class, 'categories_productes']);
    Route::put('set-estat/{comanda}',[ComandaController::class, 'set_estat']);

    //Dashboard
    Route::get('get-comptadors', [DashboardController::class, 'comptadors']);
    
        //middleware scope USER_________________
        Route::group(['middleware' => 'scope:user'], function() {

        });

        //middleware scope ADMIN_________________
        Route::group(['middleware' => 'scope:admin'], function() {

            //Route::resource('categories', 'CategoriaController');
            //canviar nom paràmetre id, cal ser com model
            Route::resource('categories', CategoriaController::class)
            ->parameters(['categories' => 'categoria']);
            
            Route::resource('productes', ProducteController::class)
            ->parameters(['productes' => 'producte']);
            Route::get('productes-categoria/{categoria}',[ProducteController::class, 'productes_categoria']);
            Route::get('get-categories', [ProducteController::class, 'categories']);
        });

});





