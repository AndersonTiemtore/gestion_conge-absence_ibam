<?php

namespace Tests\Unit\Models;

use App\Models\Employe;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class EmployeTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_belongs_to_user()
    {
        $employe = new Employe();
        
        $this->assertInstanceOf(BelongsTo::class, $employe->user());
    }

    #[Test]
    public function it_has_many_demandes()
    {
        $employe = new Employe();
        
        $this->assertInstanceOf(HasMany::class, $employe->demandes());
    }

    #[Test]
    public function it_uses_uuids()
    {
        $employe = new Employe();
        
        $this->assertContains(HasUuids::class, class_uses_recursive($employe));
    }
}