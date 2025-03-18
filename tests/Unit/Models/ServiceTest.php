<?php

namespace Tests\Unit\Models;

use App\Models\Service;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ServiceTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_has_many_users()
    {
        $service = new Service();
        
        $this->assertInstanceOf(HasMany::class, $service->users());
    }

    #[Test]
    public function it_uses_uuids()
    {
        $service = new Service();
        
        $this->assertContains(HasUuids::class, class_uses_recursive($service));
    }
}