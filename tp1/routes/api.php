<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Routes;

Route::get('/films', 'App\Http\Controllers\FilmController@index');

Route::get('/critics', 'App\Http\Controllers\CriticController@index');

Route::get('/filmActors/{id}', 'App\Http\Controllers\FilmController@showActors');

Route::get('/filmCritics/{id}', 'App\Http\Controllers\FilmController@showFilmWithCritics');