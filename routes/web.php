<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/mailable', function () {
    $username = 'Morgan';
    $password = 'password';
    $email = 'morganlconnolly@gmail.com';
    $numOfUsers = '1000';
    $popularPokemon = 'Pikachu';
    return new App\Mail\WelcomeEmail($username, $password, $email, $numOfUsers, $popularPokemon);
});