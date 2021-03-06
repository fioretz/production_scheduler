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
Route::group(['middleware' => ['auth']], function() {
    Route::get('/', ['uses' => 'DashboardController@index', 'as' => 'dashboard.index']);

    Route::get('/user', ['uses' => 'UserController@show', 'as' => 'user.show']);
    Route::get('/user/{id}', ['uses' => 'UserController@getUserById', 'as' => 'user.getUserById']);
    Route::post('/user/store', ['uses' => 'UserController@store', 'as' => 'user.store']);
    Route::post('/user/update', ['uses' => 'UserController@update', 'as' => 'user.update']);

    Route::get('/role', ['uses' => 'RoleController@show', 'as' => 'role.show']);
    Route::get('/role/{id}', ['uses' => 'RoleController@getRoleById', 'as' => 'role.getRoleById']);
    Route::post('/role/store', ['uses' => 'RoleController@store', 'as' => 'role.store']);
    Route::post('/role/update', ['uses' => 'RoleController@update', 'as' => 'role.update']);
    Route::post('/role/{id}/delete', ['uses' => 'RoleController@delete', 'as' => 'role.delete']);

    Route::get('/profilo', ['uses' => 'ProfiloController@show', 'as' => 'profilo.show']);
    Route::post('/profilo/updatenamemail', ['uses' => 'ProfiloController@updateNameMail', 'as' => 'profilo.updateNameMail']);
    Route::post('/profilo/updatepassword', ['uses' => 'ProfiloController@updatePassword', 'as' => 'profilo.updatePassword']);

    Route::get('/macchina', ['uses' => 'MacchinaController@show', 'as' => 'macchina.show']);
    Route::get('/macchina/{id}', ['uses' => 'MacchinaController@getMacchinaById', 'as' => 'macchina.getMacchinaById']);
    Route::post('/macchina/store', ['uses' => 'MacchinaController@store', 'as' => 'macchina.store']);
    Route::post('/macchina/update', ['uses' => 'MacchinaController@update', 'as' => 'macchina.update']);
    Route::post('/macchina/{id}/delete', ['uses' => 'MacchinaController@delete', 'as' => 'macchina.delete']);

    Route::get('/tipomacchina', ['uses' => 'TipoMacchinaController@show', 'as' => 'tipomacchina.show']);
    Route::get('/tipomacchina/{id}', ['uses' => 'TipoMacchinaController@getTipoMacchinaById', 'as' => 'tipomacchina.getTipoMacchinaById']);
    Route::post('/tipomacchina/store', ['uses' => 'TipoMacchinaController@store', 'as' => 'tipomacchina.store']);
    Route::post('/tipomacchina/update', ['uses' => 'TipoMacchinaController@update', 'as' => 'tipomacchina.update']);
    Route::post('/tipomacchina/{id}/delete', ['uses' => 'TipoMacchinaController@delete', 'as' => 'tipomacchina.delete']);

    Route::get('/prodotto', ['uses' => 'ProdottoController@show', 'as' => 'prodotto.show']);
    Route::get('/prodotto/{id}', ['uses' => 'ProdottoController@getProdottoById', 'as' => 'prodotto.getProdottoById']);
    Route::post('/prodotto/store', ['uses' => 'ProdottoController@store', 'as' => 'prodotto.store']);
    Route::post('/prodotto/update', ['uses' => 'ProdottoController@update', 'as' => 'prodotto.update']);
    Route::post('/prodotto/{id}/delete', ['uses' => 'ProdottoController@delete', 'as' => 'prodotto.delete']);

    Route::get('/ordineproduzione', ['uses' => 'OrdineProduzioneController@show', 'as' => 'ordineproduzione.show']);
    Route::get('/ordineproduzione/ultimonumeroordine', ['uses' => 'OrdineProduzioneController@getUltimoNumeroProduzione', 'as' => 'ordineproduzione.getUltimoNumeroProduzione']);
    Route::get('/ordineproduzione/getordineproduzionebyid/{id}', ['uses' => 'OrdineProduzioneController@getOrdineProduzioneById', 'as' => 'ordineproduzione.getOrdineProduzioneById']);
    Route::post('/ordineproduzione/tempoproduzione', ['uses' => 'OrdineProduzioneController@getTempoProduzioneByProdottoIdAndQuantita', 'as' => 'ordineproduzione.getTempoProduzioneByProdottoIdAndQuantita']);
    Route::post('/ordineproduzione/store', ['uses' => 'OrdineProduzioneController@store', 'as' => 'ordineproduzione.store']);
    Route::post('/ordineproduzione/update', ['uses' => 'OrdineProduzioneController@update', 'as' => 'ordineproduzione.update']);
    Route::post('/ordineproduzione/{id}/delete', ['uses' => 'OrdineProduzioneController@delete', 'as' => 'ordineproduzione.delete']);
    Route::get('/ordineproduzione/ordinichiusi', ['uses' => 'OrdineProduzioneController@showOrdiniChiusi', 'as' => 'ordineproduzione.showOrdiniChiusi']);
    Route::post('/ordineproduzione/{id}/open', ['uses' => 'OrdineProduzioneController@open', 'as' => 'ordineproduzione.open']);
    Route::post('/ordineproduzione/{id}/close', ['uses' => 'OrdineProduzioneController@close', 'as' => 'ordineproduzione.close']);

    Route::get('/pianificazioneproduzione/creapianificazioneform', ['uses' => 'PianificazioneProduzioneTestaController@creaPianificazioneForm', 'as' => 'pianificazioneproduzione.creaPianificazioneForm']);
    Route::post('/pianificazioneproduzione/creapianificazione', ['uses' => 'PianificazioneProduzioneTestaController@creaPianificazione', 'as' => 'pianificazioneproduzione.creaPianificazione']);
    Route::get('/pianificazioneproduzione', ['uses' => 'PianificazioneProduzioneTestaController@show', 'as' => 'pianificazioneproduzione.show']);
    Route::post('/pianificazioneproduzione/{id}/delete', ['uses' => 'PianificazioneProduzioneTestaController@delete', 'as' => 'pianificazioneproduzione.delete']);
    Route::get('/pianificazioneproduzione/{id}', ['uses' => 'PianificazioneProduzioneTestaController@getPianificazioneById', 'as' => 'pianificazioneproduzione.getPianificazioneById']);
});

Auth::routes([
    'register' => false,
    'reset' => false,
    'email' => false,
    'confirm' => false,
    'verify' => false
]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
