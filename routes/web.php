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

Route::get('/', 'AnasayfaController@index');

Route::get('users/{name}/{id?}', function ($name, $id=0) {
    return "name : $name -> $id";
})->name('users');

Route::get('detail', function () {
    return redirect()->route('users', ['name'=>'emre', 'id'=>7]);
});