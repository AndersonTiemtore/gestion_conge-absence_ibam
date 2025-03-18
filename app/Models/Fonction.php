<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fonction extends Model
{
    /** @use HasFactory<\Database\Factories\FonctionFactory> */
    use HasFactory, HasUuids;

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
