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

Route::get('/', function () {
    return view('welcome');
});



Route::get('/booklists', "BookListsController@create");
Route::get('/bookcards', "BookCardsController@create");

Route::get('/longpoll', "TelegramBotMessagesController@longpoll");

Route::get('/entity/{entityId}', function($entityId)
{


});