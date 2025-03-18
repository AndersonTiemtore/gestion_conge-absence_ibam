<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\DashboardController;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    #[Test]
    public function it_returns_dashboard_view()
    {
        $controller = new DashboardController();
        $view = $controller->index();
        
        $this->assertEquals('pages.backend.dashboard', $view->name());
    }
}