<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Routes;

Route::get('/films', 'App\Http\Controllers\FilmController@index');