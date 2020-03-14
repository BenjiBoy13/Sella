<?php

namespace Stella;

use Stella\Core\Router;
use Stella\Modules\Http\Http;

class Kernel
{
    /**
     * Kernel constructor.
     * @throws Exceptions\Core\Configuration\ConfigurationFileNotFoundException
     * @throws Exceptions\Core\Configuration\ConfigurationFileNotYmlException
     * @throws Exceptions\Core\Routing\ActionNotFoundException
     * @throws Exceptions\Core\Routing\ControllerNotFoundException
     * @throws Exceptions\Core\Routing\NoRoutesFoundException
     */
    public function __construct ()
    {
        $router = new Router();
        $http = new Http();

        set_exception_handler([$this, 'exception_handler']);



        $method = $http->retrieveRequestedPath()['method'];
        $uri = $http->retrieveRequestedPath()['uri'];

        $router->enableRouting($uri, $method);
    }

    public function exception_handler (\Throwable $e)
    {
        echo "<pre>";

        print_r($e->getMessage() . "\n");
        print_r($e->getFile() . "\n");
        print_r($e->getLine() . "\n\n");

        print_r($e->getPrevious());

        echo "</pre>";
    }
}

