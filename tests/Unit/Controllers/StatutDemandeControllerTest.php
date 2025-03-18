<?php
namespace Tests\Unit\Controllers;
use App\Http\Controllers\StatutDemandeController;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
class StatutDemandeControllerTest extends TestCase
{
 #[Test]
public function it_has_index_method()
 {
$controller = new StatutDemandeController();
$response = $controller->index();
$this->assertNull($response);
 }
 #[Test]
public function it_has_create_method()
 {
$controller = new StatutDemandeController();
$response = $controller->create();
$this->assertNull($response);
 }
 #[Test]
public function it_has_store_method()
 {
$controller = new StatutDemandeController();
$request = new Request();
$response = $controller->store($request);
$this->assertNull($response);
 }
 #[Test]
public function it_has_show_method()
 {
$controller = new StatutDemandeController();
$response = $controller->show('1');
$this->assertNull($response);
 }
 #[Test]
public function it_has_edit_method()
 {
$controller = new StatutDemandeController();
$response = $controller->edit('1');
$this->assertNull($response);
 }
 #[Test]
public function it_has_update_method()
 {
$controller = new StatutDemandeController();
$request = new Request();
$response = $controller->update($request, '1');
$this->assertNull($response);
 }
 #[Test]
public function it_has_destroy_method()
 {
$controller = new StatutDemandeController();
$response = $controller->destroy('1');
$this->assertNull($response);
 }
}