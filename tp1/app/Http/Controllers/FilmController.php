<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Film;
use App\Http\Resources\FilmResource;
use App\Http\Resources\ActorResource;
use App\Http\Resources\CriticResource;
use Exception;

class FilmController extends Controller
{
    const SERVER_ERROR_MESSAGE = 'Server error';
    const FILM_NOT_FOUND_MESSAGE = 'Film not found';

    public function index()
    {
        try {
            return FilmResource::collection(Film::paginate(20))->response()->setStatusCode(200);
        } catch (Exception $e) {
            return response()->json(['message' => self::SERVER_ERROR_MESSAGE], 500);
        }
    }

    public function showActors($filmId)
    {
        try {
            $film = Film::findOrFail($filmId);
    
            $actors = $film->actors()->paginate(20);

            if ($actors->isEmpty()) {
                return response()->json([], 204);
            }

            return ActorResource::collection($actors)->response()->setStatusCode(200);

        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => self::FILM_NOT_FOUND_MESSAGE], 404);

        } catch (Exception $e) {
            return response()->json(['message' => self::SERVER_ERROR_MESSAGE], 500);
        }
    }

    public function showFilmWithCritics($filmId)
    {
        try {
            $film = Film::findOrFail($filmId);
            $critics = $film->critics()->paginate(20);

            if ($critics->isEmpty()) {
                return response()->json([], 204);
            }

            return response()->json([
                'film' => new FilmResource($film),
                'critics' => CriticResource::collection($critics)
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => self::FILM_NOT_FOUND_MESSAGE], 404);
            
        } catch (Exception $e) {
            return response()->json(['message' => self::SERVER_ERROR_MESSAGE], 500);
        }
    }

    public function averageScore($id)
    {
        try {
            $film = Film::findOrFail($id);
    
            return response()->json([
                'film' => $film->title,
                'average_score' => round($film->averageScore(), 1),
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message' => self::SERVER_ERROR_MESSAGE], 500);
        }
    }

    public function search(Request $request)
    {
        try {
            $query = Film::query();
    
            if ($request->has('keyword')) {
                $query->where('title', 'like', '%' . $request->get('keyword') . '%');
            }
    
            if ($request->has('rating')) {
                $query->where('rating', 'like', '%' . $request->get('rating') . '%');
            }
    
            if ($request->has('minLength')) {
                $query->where('length', '>=', $request->get('minLength'));
            }
    
            if ($request->has('maxLength')) {
                $query->where('length', '<=', $request->get('maxLength'));
            }
    
            $films = $query->paginate(20);
    
            return response()->json($films, 200);
        } catch (Exception $e) {
            return response()->json(['message' => self::SERVER_ERROR_MESSAGE], 500);
        }
    }
}
