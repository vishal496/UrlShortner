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
    return view('shortenpage');
});

Route::get('/signup', function () {
    return view('register');
});

Route::get('/logout', function () {
    Session::flush();
    return redirect('/');
});

Route::post('/make','LinkController@make');

Route::post('login','LoginController@auth');

Route::post('/register','RegisterController@register');

Route::get('/{hash}',['as'=>'web','uses'=>'LinkController@get']);
?>
