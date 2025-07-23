<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Favourite extends Model
{
    protected $fillable = ['user_id', 'pokemon_id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function pokemon() {
        return $this->belongsTo(User::class);
    }
}
