<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', ['uses' => 'DashboardController@index', 'as' => 'dashboard.index']);

Route::get('/tipomacchina', ['uses' => 'TipoMacchinaController@show', 'as' => 'tipomacchina.show']);
Route::get('/tipomacchina/{id}', ['uses' => 'TipoMacchinaController@getTipoMacchinaById', 'as' => 'tipomacchina.getTipoMacchinaById']);
Route::post('/tipomacchina/store', ['uses' => 'TipoMacchinaController@store', 'as' => 'tipomacchina.store']);
Route::post('/tipomacchina/update', ['uses' => 'TipoMacchinaController@update', 'as' => 'tipomacchina.update']);
Route::post('/tipomacchina/{id}/delete', ['uses' => 'TipoMacchinaController@delete', 'as' => 'tipomacchina.delete']);

Route::get('/macchina', ['uses' => 'MacchinaController@show', 'as' => 'macchina.show']);
Route::get('/macchina/{id}', ['uses' => 'MacchinaController@getMacchinaById', 'as' => 'macchina.getMacchinaById']);
Route::post('/macchina/store', ['uses' => 'MacchinaController@store', 'as' => 'macchina.store']);
Route::post('/macchina/update', ['uses' => 'MacchinaController@update', 'as' => 'macchina.update']);
Route::post('/macchina/{id}/delete', ['uses' => 'MacchinaController@delete', 'as' => 'macchina.delete']);

Route::get('/prodotto', ['uses' => 'ProdottoController@show', 'as' => 'prodotto.show']);
Route::get('/prodotto/{id}', ['uses' => 'ProdottoController@getProdottoById', 'as' => 'prodotto.getProdottoById']);
Route::post('/prodotto/store', ['uses' => 'ProdottoController@store', 'as' => 'prodotto.store']);
Route::post('/prodotto/update', ['uses' => 'ProdottoController@update', 'as' => 'prodotto.update']);
Route::post('/prodotto/{id}/delete', ['uses' => 'ProdottoController@delete', 'as' => 'prodotto.delete']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
