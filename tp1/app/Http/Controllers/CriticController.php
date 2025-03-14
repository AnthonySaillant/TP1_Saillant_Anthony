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

    /**
     * @SWG\Delete(
     *     path="/api/critics/{id}",
     *     summary="Delete a critic",
     *     description="Delete a critic from the database by its ID.",
     *     tags={"Critics"},
     *     @SWG\Response(
     *         response=200,
     *         description="Critic deleted successfully."
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Critic not found."
     *     )
     * )
     */
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
