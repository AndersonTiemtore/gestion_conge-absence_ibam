<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\ResponsableController;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ResponsableControllerTest extends TestCase
{
    #[Test]
    public function it_returns_responsable_index_view()
    {
        $controller = new ResponsableController();
        $request = new Request();
        
        $view = $controller->__invoke($request);
        
        $this->assertEquals('pages.backend.responsable.index', $view->name());
    }
}