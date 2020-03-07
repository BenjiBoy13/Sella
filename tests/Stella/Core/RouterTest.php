<?php


use Stella\Core\Router;

class RouterTest extends \PHPUnit\Framework\TestCase
{
    protected Router $router;

    protected function setUp(): void
    {
        $this->router = new Router();
    }

    public function test_match_cases_with_uris ()
    {
        $this->assertEquals(true, $this->router->enableRouting("/books/show/1", "GET"));
    }

    public function test_no_route_mach_exception ()
    {
        $this->expectException(\Stella\Exceptions\Core\Routing\NoRoutesFoundException::class);
        $this->expectExceptionMessage("404, No route was found for requested URI");
        $this->router->enableRouting("/something/1", "GET");
    }

    public function test_router_match_exception_controller_not_valid ()
    {
        $this->expectException(\Stella\Exceptions\Core\Routing\ControllerNotFoundException::class);
        $this->expectExceptionMessage("Controller file found but is not a valid controller, expects to extend from StellaController");
        $this->router->enableRouting("/users/show", "GET");
    }

    public function test_router_mach_exception_controller_not_found ()
    {
        $this->expectException(\Stella\Exceptions\Core\Routing\ControllerNotFoundException::class);
        $this->expectExceptionMessage("Controller not found in App\Controllers\RootController");
        $this->router->enableRouting("/", "GET");
    }

    public function test_router_match_exception_method_not_found ()
    {
        $this->expectException(\Stella\Exceptions\Core\Routing\ActionNotFoundException::class);
        $this->expectExceptionMessage("Method allAction not found in App\Controllers\BooksController");
        $this->router->enableRouting("/books/all", "GET");
    }
}