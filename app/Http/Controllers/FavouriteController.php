<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favourite;
use App\Models\Pokemon;
use App\Services\PokemonService;
use Exception;


class FavouriteController extends Controller {

    public function __construct(private PokemonService $pokemonService) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        $userID = $request->user()->id;
        $favouritePokemonIDs = Favourite::select('pokemon_id')->where('user_id', '=', $userID)->get();
        $favouritePokemon = Pokemon::whereIn('id', $favouritePokemonIDs)->select('id', 'name')->get();

        return response()->json([
            'favourites' => $favouritePokemon->map(function ($pokemon) {
                return [
                    'id' => $pokemon->id,
                    'name' => $pokemon->name,
                ];
            })
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        // Request body should be JSON w/ name & data keys.
        // Data validation.
        $request->validate([
            'name' => 'required|string',
            'colour' => 'required|string',
            'data' => 'required|array',
        ]);

        $userID = $request->user()->id;
        $pokemon = $this->pokemonService->findPokemon($request->input('name'));

        try {
            if (empty($pokemon)) {                                      // Create pokemon record if doesn't exist
                $pokemon = $this->pokemonService->addPokemon($request);
            } else {
                $isNotFavourite = Favourite::where('user_id', $userID)->where('pokemon_id', $pokemon->id)->get()->isEmpty(); // Check if already favourited.
                if (!$isNotFavourite) {
                    return response()->json(['error' => 'Pokemon is already in favourites'], 409);
                }
            }

            Favourite::create([
                'user_id' => $userID,
                'pokemon_id' => $pokemon->id,
            ]);

            return response()->noContent();
        } catch (Exception) {
            return response()->json(['error' => 'Failed to add Pokemon'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $pokemonID) {
        try{
            $userID = $request->user()->id;
            $isNotFavourite = Favourite::where('user_id', $userID)->where('pokemon_id', $pokemonID)->get()->isEmpty();
            if ($isNotFavourite) {
                return response()->json(['error' => 'Pokemon not in favourites, check request parameters'], 404);
            }

            $pokemon = $this->pokemonService->findPokemon($pokemonID);

            if ($pokemon) {
                return response()->json($pokemon, 200);
            }
            return response()->json(['error' => 'Pokemon not found, check request parameters'], 404);
        } catch (Exception) {
        return response()->json(['error' => 'Failed to fetch Pokemon'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $pokemonID) {
        try {
            $userID = $request->user()->id;
            $favouritesWithID = Favourite::where('pokemon_id', $pokemonID)->get();
            $userFavourite = Favourite::where([['pokemon_id', $pokemonID],['user_id', $userID]]);

            // Check if it's in their favourites.
            if ($userFavourite->get()->isEmpty()) {
                return response()->json(['error' => 'Pokemon not in favourites, check request parameters'], 404);
            }

            if ($favouritesWithID->count() == 1) {
                $pokemon = $this->pokemonService->findPokemon($pokemonID);
                $pokemon->delete();
            }

            $userFavourite->delete();

            return response()->noContent();
        } catch (Exception) {
            return response()->json(['error' => 'Failed to remove Pokemon'], 500);
        }
    }
}