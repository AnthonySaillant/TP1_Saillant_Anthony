<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Exception;

class UserController extends Controller
{
    const SERVER_ERROR = 'Server error';
    const NO_REVIEWS_FOUND = 'No reviews found for this user.';
    const USER_NOT_FOUND_MESSAGE = 'User not found';

    /**
     * @SWG\Post(
     *     path="/api/users",
     *     summary="Create a new user",
     *     description="Store a new user in the database.",
     *     tags={"Users"},
     *     @SWG\Response(
     *         response=201,
     *         description="User created successfully."
     *     ),
     *     @SWG\Response(
     *         response=500,
     *         description="Server error."
     *     )
     * )
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $user = User::create($request->validated());
            return (new UserResource($user))->response()->setStatusCode(201);
        } catch (Exception $e) {
            return response()->json(['message' => self::SERVER_ERROR], 500);
        }
    }

    /**
     * @SWG\Put(
     *     path="/api/users/{id}",
     *     summary="Update an existing user",
     *     description="Update a user's information by ID.",
     *     tags={"Users"},
     *     @SWG\Response(
     *         response=200,
     *         description="User updated successfully."
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="User not found."
     *     )
     * )
     */
    public function update(UpdateUserRequest $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->update($request->validated());
            return (new UserResource($user))->response()->setStatusCode(200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => self::USER_NOT_FOUND_MESSAGE], 404);
        } catch (Exception $e) {
            return response()->json(['message' => self::SERVER_ERROR], 500);
        }
    }

    /**
     * @SWG\Get(
     *     path="/api/users/{userId}/preferred-language",
     *     summary="Get the preferred language of a user",
     *     description="Fetch the most frequent language from the user's reviews.",
     *     tags={"Users"},
     *     @SWG\Response(
     *         response=200,
     *         description="Preferred language of the user."
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="No reviews found for this user."
     *     )
     * )
     */
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
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => self::USER_NOT_FOUND_MESSAGE], 404);
        } catch (Exception $e) {
            return response()->json(['message' => self::SERVER_ERROR], 500);
        }
    }
}
