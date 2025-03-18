<?php

namespace Tests\Unit\Models;

use App\Models\Fonction;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class FonctionTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_has_many_users()
    {
        $fonction = new Fonction();
        
        $this->assertInstanceOf(HasMany::class, $fonction->users());
    }

    #[Test]
    public function it_uses_uuids()
    {
        $fonction = new Fonction();
        
        $this->assertContains(HasUuids::class, class_uses_recursive($fonction));
    }
}