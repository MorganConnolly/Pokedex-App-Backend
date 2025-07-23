<?php

namespace App\Services;

use App\Models\User;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail;
use App\Models\Favourite;
use App\Models\Pokemon;

class MailService
{
    public function sendWelcome($user) {
        $numOfUsers = User::all()->count();
        $popularPokemonID = Favourite::select('pokemon_id')
            ->groupBy('pokemon_id')
            ->orderByRaw('COUNT(*) DESC')
            ->pluck('pokemon_id')
            ->first();
        $popularPokemon = Pokemon::select('name')->where('id', $popularPokemonID)->value('name');

        Mail::to($user->email)->send(new WelcomeEmail($user->name, $user->password, $user->email, $numOfUsers, $popularPokemon));
    }
}
