<?php

namespace Tests\Unit\Models;

use App\Models\DemandeConge;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class DemandeCongeTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_has_fillable_attributes()
    {
        $fillable = ['statut_demande_id'];
        $demande = new DemandeConge();
        
        $this->assertEquals($fillable, $demande->getFillable());
    }

    #[Test]
    public function it_belongs_to_employe()
    {
        $demande = new DemandeConge();
        
        $this->assertInstanceOf(BelongsTo::class, $demande->employe());
    }

    #[Test]
    public function it_belongs_to_statut_demande()
    {
        $demande = new DemandeConge();
        
        $this->assertInstanceOf(BelongsTo::class, $demande->statutDemande());
    }

    #[Test]
    public function it_has_notifications()
    {
        $demande = new DemandeConge();
        
        $this->assertIsObject($demande->notifications());
    }

    #[Test]
    public function it_uses_uuids()
    {
        $demande = new DemandeConge();
        
        $this->assertContains(HasUuids::class, class_uses_recursive($demande));
    }
}