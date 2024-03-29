<?php

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

Route::get('/', 'IndexController@index');

Route::get('/admin', 'AdminController@index');
Route::get('/procurementPdf/{id}','fileController@showProcurement');
Route::get('/SpkPdf/{id}','fileController@showWorkOrder');

Route::get('/admin/{any}', function () {
    return view('admin');
})->where('any', '[\/\w\.-]*');

Route::get('/{any}',function(){
    return view('index');
})->where('any','[\/\w\.-]*');
