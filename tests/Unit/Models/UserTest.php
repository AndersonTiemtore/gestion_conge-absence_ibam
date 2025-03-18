<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_has_fillable_attributes()
    {
        $fillable = ['name', 'email', 'password'];
        $user = new User();
        
        $this->assertEquals($fillable, $user->getFillable());
    }

    #[Test]
    public function it_has_hidden_attributes()
    {
        $hidden = ['password', 'remember_token'];
        $user = new User();
        
        $this->assertEquals($hidden, $user->getHidden());
    }

    #[Test]
    public function it_has_casts()
    {
        $user = new User();
        $casts = $user->getCasts();
        
        $this->assertArrayHasKey('email_verified_at', $casts);
        $this->assertArrayHasKey('password', $casts);
        $this->assertEquals('datetime', $casts['email_verified_at']);
        $this->assertEquals('hashed', $casts['password']);
    }

    #[Test]
    public function it_belongs_to_service()
    {
        $user = new User();
        
        $this->assertInstanceOf(BelongsTo::class, $user->service());
    }

    #[Test]
    public function it_has_one_employe()
    {
        $user = new User();
        
        $this->assertInstanceOf(HasOne::class, $user->employe());
    }

    #[Test]
    public function it_has_one_grh()
    {
        $user = new User();
        
        $this->assertInstanceOf(HasOne::class, $user->grh());
    }

    #[Test]
    public function it_has_one_responsable()
    {
        $user = new User();
        
        $this->assertInstanceOf(HasOne::class, $user->responsable());
    }

    #[Test]
    public function it_belongs_to_fonction()
    {
        $user = new User();
        
        $this->assertInstanceOf(BelongsTo::class, $user->fonction());
    }
}