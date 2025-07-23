<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pokemon;
use App\Services\PokemonService;
use Exception;

// Requests need headers: content-type: application/json & Accept: application/json.
class PokemonController extends Controller
{   
    protected $pokemonService;

    public function __construct(PokemonService $pokemonService) {
        $this->pokemonService = $pokemonService;
    }

    /**
     * Returns a summary of all stored pokemon to enable further api access.
     */
    public function index(Request $request)
    {
        $pokemonSummary = Pokemon::select('id', 'name')->get();

        return response()->json($pokemonSummary, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        // Request body should be JSON w/ name & data keys.
        // Data validation.
        $request->validate([
            'name' => 'required|string',
            'colour' => 'required|string',
            'data' => 'required|array',
        ]);
        
        try {
            $this->pokemonService->addPokemon($request);

            return response()->noContent();
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to add Pokemon'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $identifier)
    {
        $pokemon = $this->pokemonService->findPokemon($identifier);

        if ($pokemon) {
            return response()->json($pokemon, 200);
        }
        return response()->json(['error' => 'Resource not found, check request parameters'], 404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $identifier)
    {
        $pokemon = $this->pokemonService->findPokemon($identifier);

        if ($pokemon) {
            $pokemon->delete();
            return response()->noContent();
        }

        return response()->json(['error' =>'Resource not found, check request parameters'], 404);
    }
}
