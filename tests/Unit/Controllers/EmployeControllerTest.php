<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\EmployeController;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EmployeControllerTest extends TestCase
{
    #[Test]
    public function it_returns_employe_index_view()
    {
        $controller = new EmployeController();
        $request = new Request();
        
        $view = $controller->__invoke($request);
        
        $this->assertEquals('pages.backend.employe.index', $view->name());
    }
}