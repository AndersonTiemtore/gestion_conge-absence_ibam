<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\FonctionController;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FonctionControllerTest extends TestCase
{
    #[Test]
    public function it_returns_fonction_index_view()
    {
        $controller = new FonctionController();
        $request = new Request();
        
        $view = $controller->__invoke($request);
        
        $this->assertEquals('pages.backend.fonction.index', $view->name());
    }
}