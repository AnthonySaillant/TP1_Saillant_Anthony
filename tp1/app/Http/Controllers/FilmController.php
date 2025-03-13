<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Film;
use App\Http\Resources\FilmResource;
use App\Http\Resources\ActorResource;
use App\Http\Resources\CriticResource;

class FilmController extends Controller
{
    public function index()
    {
        return FilmResource::collection(Film::all());
    }

    public function showActors($filmId)
    {
        $film = Film::findOrFail($filmId);

        $actors = $film->actors;
        return ActorResource::collection($actors);
    }

    public function showFilmWithCritics($filmId)
    {
        $film = Film::findOrFail($filmId);
        $critics = $film->critics;

        return CriticResource::collection($critics);
    }
}
