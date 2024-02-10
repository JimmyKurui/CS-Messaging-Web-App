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
    $users = User::all();
    // dd(($users[0]->id));
    $agents = Agent::all();
    return view('welcome', compact('users', 'agents'));
});


Route::post('/support', 'App\Http\Controllers\UserController@index');
Route::post('/dashboard', 'App\Http\Controllers\AgentController@index');

Route::get('/messages', 'App\Http\Controllers\MessageController@index');
Route::post('/messages/broadcast', 'App\Http\Controllers\MessageController@broadcast');
Route::post('/messages/receive', 'App\Http\Controllers\MessageController@receive');