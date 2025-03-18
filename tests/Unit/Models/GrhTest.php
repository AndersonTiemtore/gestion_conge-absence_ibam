<?php

namespace Tests\Unit\Models;

use App\Models\Grh;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class GrhTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_belongs_to_user()
    {
        $grh = new Grh();
        
        $this->assertInstanceOf(BelongsTo::class, $grh->user());
    }

    #[Test]
    public function it_uses_uuids()
    {
        $grh = new Grh();
        
        $this->assertContains(HasUuids::class, class_uses_recursive($grh));
    }
}