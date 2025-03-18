<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StatutDemande extends Model
{
    /** @use HasFactory<\Database\Factories\StatutDemandeFactory> */
    use HasFactory, HasUuids;

    protected $fillable = ['statut'];

    public function demandes(): HasMany
    {
        return $this->hasMany(DemandeConge::class);
    }
}
