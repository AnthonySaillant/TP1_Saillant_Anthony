<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Exception;

class UserController extends Controller
{
    const SERVER_ERROR = 'Server error';
    const NO_REVIEWS_FOUND = 'No reviews found for this user.';

    public function store(StoreUserRequest $request)
    {
        try {
            $user = User::create($request->validated());
            return (new UserResource($user))->response()->setStatusCode(201);
        } catch (Exception $e) {
            return response()->json(['message' => self::SERVER_ERROR], 500);
        }
    }

    public function update(UpdateUserRequest $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->update($request->validated());
            return (new UserResource($user))->response()->setStatusCode(200);
        } catch (Exception $e) {
            return response()->json(['message' => self::SERVER_ERROR], 500);
        }
    }

    public function getPreferredLanguage($userId)
    {
        try {
            $user = User::with('critics.film.language')->findOrFail($userId);
            $critics = $user->critics;

            if ($critics->isEmpty()) {
                return response()->json(['message' => self::NO_REVIEWS_FOUND], 404);
            }

            $languageCount = $critics->map(function ($critic) {
                return $critic->film->language->name ?? 'Unknown';  
            })->countBy();

            $preferredLanguage = $languageCount->sortDesc()->keys()->first();

            return response()->json([
                'user' => $user->id,
                'preferred_language' => $preferredLanguage,
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message' => self::SERVER_ERROR], 500);
        }
    }
}
