<?php

namespace Tests\Unit\Models;

use App\Models\StatutDemande;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class StatutDemandeTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_has_fillable_attributes()
    {
        $fillable = ['statut'];
        $statut = new StatutDemande();
        
        $this->assertEquals($fillable, $statut->getFillable());
    }

    #[Test]
    public function it_has_many_demandes()
    {
        $statut = new StatutDemande();
        
        $this->assertInstanceOf(HasMany::class, $statut->demandes());
    }

    #[Test]
    public function it_uses_uuids()
    {
        $statut = new StatutDemande();
        
        $this->assertContains(HasUuids::class, class_uses_recursive($statut));
    }
}