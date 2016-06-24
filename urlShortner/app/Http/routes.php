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


Route::get('/','LoginController@home');

Route::post('home','LoginController@auth');

Route::get('/logout','LoginController@signout');

Route::post('/make','LinkController@createCode');

Route::post('/register','LoginController@register');

Route::get('/signup','LoginController@registerView');

Route::get('/{hash}',['as'=>'web','uses'=>'LinkController@redirect']);

Route::post('/{hash}',['as'=>'state','uses'=>'LinkController@action']);
?>
