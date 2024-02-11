<?php

use App\Models\User;
use App\Models\Agent;
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

Route::get('/', function () {
    return view('layouts.app');
});


// Route::post('/support', 'App\Http\Controllers\UserController@index');
// Route::post('/dashboard', 'App\Http\Controllers\AgentController@index');

Route::post('/broadcast', 'App\Http\Controllers\MessageController@store');
