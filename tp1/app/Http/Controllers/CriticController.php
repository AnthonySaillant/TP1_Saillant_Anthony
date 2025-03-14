<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\CriticResource;
use App\Models\Critic;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CriticController extends Controller
{
    const SERVER_ERROR = 'Server error';
    const CRITIC_NOT_FOUND = 'Critic not found';

    public function index()
    {
        try {
            return CriticResource::collection(Critic::paginate(20))->response()->setStatusCode(200);
        } catch (Exception $e) {
            return response()->json(['message' => self::SERVER_ERROR], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $critic = Critic::findOrFail($id);
            $critic->delete();
            return response()->json(['message' => 'Critique deleted successfully.'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => self::CRITIC_NOT_FOUND], 404);
        } catch (Exception $e) {
            return response()->json(['message' => self::SERVER_ERROR], 500);
        }
    }
}
