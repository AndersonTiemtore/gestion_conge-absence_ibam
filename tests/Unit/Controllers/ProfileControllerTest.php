<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    #[Test]
    public function it_returns_profile_index_view()
    {
        $controller = new ProfileController();
        $request = new Request();
        
        $view = $controller->__invoke($request);
        
        $this->assertEquals('pages.backend.profile.index', $view->name());
    }
}