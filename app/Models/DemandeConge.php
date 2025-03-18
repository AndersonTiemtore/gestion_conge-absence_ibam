<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\DatabaseNotification;

class DemandeConge extends Model
{
    /** @use HasFactory<\Database\Factories\DemandeCongeFactory> */
    use HasFactory, HasUuids;

    protected $fillable = [
        'statut_demande_id',
    ];

    public function employe(): BelongsTo
    {
        return $this->belongsTo(Employe::class);
    }

    public function statutDemande(): BelongsTo
    {
        return $this->belongsTo(StatutDemande::class);
    }

    public function notifications()
    {
        return $this->morphMany(DatabaseNotification::class, 'notifiable');
    }
}
