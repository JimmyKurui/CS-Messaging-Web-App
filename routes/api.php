<?php

use App\Models\Agent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/home', function () {
    $users = User::pluck('id');
    $agents = Agent::pluck('id');
    return response()->json(['users'=> $users, 'agents'=> $agents]);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/support', 'App\Http\Controllers\UsersController@index');
Route::get('/dashboard', 'App\Http\Controllers\AgentsController@index');

Route::get('/conversations', 'App\Http\Controllers\UsersController@getUserConversations');

Route::get('/messages', 'App\Http\Controllers\MessagesController@index');
Route::post('/messages', 'App\Http\Controllers\MessagesController@store');

Route::get('/ticket-messages', 'App\Http\Controllers\TicketMessagesController@index');
Route::post('/ticket-messages', 'App\Http\Controllers\TicketMessagesController@store');

Route::get('/tickets', 'App\Http\Controllers\TicketsController@index');
Route::post('/tickets', 'App\Http\Controllers\TicketsController@store');
