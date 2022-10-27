<?php

use Illuminate\Support\Facades\Route;

//Rotas para login / logout / verificacao
Route::get('/login','App\Http\Controllers\LoginController@index')->name('login');
Route::get('/','App\Http\Controllers\LoginController@loginRedirect')->name('login.redirect');

Route::post('/postLogin','App\Http\Controllers\LoginController@postLogin')->name('postLogin');
Route::post('/putLogin','App\Http\Controllers\LoginController@putLogin')->name('putLogin');

Route::post('/getLogin','App\Http\Controllers\LoginController@getLogin')->name('getLogin');
Route::get('/logout', 'App\Http\Controllers\LoginController@sair')->name('sair');

// Pagina principal Index
Route::get('/principal', 'App\Http\Controllers\PrincipalController@index')->name('principal');

//Testes API
Route::get('/apicentrocusto','App\Http\Controllers\CentroCustoController@index')->name('centros'); // api de centro de custos
Route::get('/apiprodutos','App\Http\Controllers\ProdutosController@index')->name('apiprodutos');
Route::any('/filtroProd', 'App\Http\Controllers\ProdutosController@filtroProd')->name('filtroProd');
Route::get('/apicentrocusto', 'App\Http\Controllers\CentroCustoController@index')->name('centros'); // api de centro de custos

//Carrinho de compras
Route::any('/postcar/{id}/{descricao}/{unidade}', 'App\Http\Controllers\CarrinhoController@postCarrinho')->name('postcar');
Route::any('/delete/{id}/', 'App\Http\Controllers\CarrinhoController@deleteCarrinho')->name('deletecar');
Route::get('carrinho/', 'App\Http\Controllers\CarrinhoController@index')->name('carrinho');

//Rotas Armazem
Route::get('/apiarmazem','App\Http\Controllers\SArmazemController@index')->name('saArmazem');
Route::get('/apiarmazemIncluido','App\Http\Controllers\SArmazemController@dashInc')->name('dashInc');
Route::get('/apiarmazemFim','App\Http\Controllers\SArmazemController@dashFim')->name('dashFim');
Route::get('/apiarmazemPC','App\Http\Controllers\SArmazemController@dashPC')->name('dashPC');
Route::get('/apiarmazemDisp','App\Http\Controllers\SArmazemController@dashDisp')->name('dashDisp');
Route::get('/filtroSA', 'App\Http\Controllers\SArmazemController@filtroSA')->name('filtroSA');
Route::post('/postsa', 'App\Http\Controllers\SArmazemController@postSA')->name('postsa');
Route::post('/putsa', 'App\Http\Controllers\SArmazemController@putSA')->name('putsa');
Route::any('saedit/{id}','App\Http\Controllers\SArmazemController@edit')->name('saedit');
Route::get('/deleteitem/{id}/{item}', 'App\Http\Controllers\SArmazemController@deleteitem')->name('deleteitem');
Route::get('/deleteall/{id}', 'App\Http\Controllers\SArmazemController@deleteall')->name('deleteall');

//Filtros:
Route::any('/filtrarpalavra', 'App\Http\Controllers\ProdutosController@buscaPalavraChave')->name('filtrarpalavra');
// ------------------------------ Fim Requisição ao Armazém ------------------