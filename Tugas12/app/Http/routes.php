<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::post('/simpan','Karyawan@simpan');
Route::get('/hapus/{id}','Karyawan@hapus');
Route::post('/ubah','Karyawan@ubah');
Route::get('/baca','Karyawan@baca');
