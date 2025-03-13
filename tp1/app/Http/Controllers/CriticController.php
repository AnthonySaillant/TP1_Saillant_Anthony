<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\CriticResource;
use App\Models\Critic;

class CriticController extends Controller
{
    public function index()
    {
        return CriticResource::collection(Critic::all());
    }
}
