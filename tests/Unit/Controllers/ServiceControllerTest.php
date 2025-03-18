<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\ServiceController;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ServiceControllerTest extends TestCase
{
    #[Test]
    public function it_returns_service_index_view()
    {
        $controller = new ServiceController();
        $request = new Request();
        
        $view = $controller->__invoke($request);
        
        $this->assertEquals('pages.backend.service.index', $view->name());
    }
}