<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Favourite;

class Pokemon extends Model
{
    protected $fillable = ['name', 'data', 'colour'];

    protected $casts = [
        'data' => 'json',                   // Convert payload data to json before saving to array.
    ];

    public function favourites() {
        return $this->hasMany(Favourite::class);
    }
}
