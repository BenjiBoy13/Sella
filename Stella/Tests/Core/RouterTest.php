<?php

namespace Stella\Tests\Core;

use Stella\Core\Router;

require_once '/Users/benjamin_gil/Sites/stella/Stella/constants.php';

class RouterTest extends \PHPUnit\Framework\TestCase
{
    protected Router $router;

    protected function setUp(): void
    {
        $this->router = new Router();
    }

    public function test_match_case ()
    {
        $this->assertEquals(true, $this->router->enableRouting( "/testing-one/a/10", "GET"));
    }

    public function test_no_route_mach_exception ()
    {
        $this->expectException(\Stella\Exceptions\Core\Routing\NoRoutesFoundException::class);
        $this->expectExceptionMessage("404, No route was found for requested URI");
        $this->router->enableRouting("/non-existent", "GET");
    }

    public function test_router_match_exception_controller_not_valid ()
    {
        $this->expectException(\Stella\Exceptions\Core\Routing\ControllerNotFoundException::class);
        $this->expectExceptionMessage("Controller file found but is not a valid controller, expects to extend from StellaController");
        $this->router->enableRouting("/testing-two/c", "GET");
    }

    public function test_router_mach_exception_controller_not_found ()
    {
        $this->expectException(\Stella\Exceptions\Core\Routing\ControllerNotFoundException::class);
        $this->expectExceptionMessage("Controller not found in Stella\Controllers\Tests\TestThirdController");
        $this->router->enableRouting("/testing-three/dont-know", "GET");
    }

    public function test_router_match_exception_method_not_found ()
    {
        $this->expectException(\Stella\Exceptions\Core\Routing\ActionNotFoundException::class);
        $this->expectExceptionMessage("Method notAnAction not found in Stella\Controllers\Tests\TestOneController");
        $this->router->enableRouting("/testing-one/b", "GET");
    }
}