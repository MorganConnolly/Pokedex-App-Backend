<?php

namespace App\Services;

use App\Models\Pokemon;
use Illuminate\Http\Request;

class PokemonService
{
    public function addPokemon(Request $request): ?Pokemon {
        $pokemon = Pokemon::create([
            'name' => strtolower($request->input('name')),
            'colour' => $request->input('colour'),
            'data' => $request->input('data'),
        ]);

        return $pokemon;
    }

    public function findPokemon(string $identifier): ?Pokemon {
        $pokemon = is_numeric($identifier) 
            ? Pokemon::find($identifier)
            : Pokemon::where('name', strtolower($identifier))->first();

        return $pokemon; // Returns null if none exists
    }
}
