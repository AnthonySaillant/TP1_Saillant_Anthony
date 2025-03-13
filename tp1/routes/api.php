<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Routes;

Route::get('/films', 'App\Http\Controllers\FilmController@index');

Route::get('/critics', 'App\Http\Controllers\CriticController@index');

Route::get('/films/{filmId}/actors', 'App\Http\Controllers\FilmController@showActors');

Route::get('/films/{filmId}/critics', 'App\Http\Controllers\FilmController@showFilmWithCritics');

Route::post('/users', 'App\Http\Controllers\UserController@store');

Route::put('/users/{userId}', 'App\Http\Controllers\UserController@update');

Route::delete('/critics/{criticId}', 'App\Http\Controllers\CriticController@destroy');

Route::get('/films/{filmId}/average-score','App\Http\Controllers\FilmController@averageScore');

Route::get('/users/{userId}/preferred-language','App\Http\Controllers\UserController@getPreferredLanguage');

Route::get('/films/{search}','App\Http\Controllers\FilmController@search');