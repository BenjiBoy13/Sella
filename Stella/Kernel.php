<?php

namespace Stella;

use Composer\Autoload\ClassLoader;
use ReflectionException;
use Stella\Core\Router;
use Stella\Modules\Http\Http;
use Symfony\Component\Dotenv\Dotenv;
use Throwable;

/**
 * -----------------------------------------
 * Class Kernel
 * -----------------------------------------
 *
 * Stella Kernel, fires up the framework by
 * taking the incoming request and hands it
 * to the router core class which will look
 * into the configuration files and launch the
 * proper controller with the method wired up to
 * the path action.
 *
 * Also all exceptions end here in the kernel class
 * to be handled properly.
 *
 * @author Benjamin Gil Flores
 * @version NaN
 * @package Stella
 */
class Kernel
{
    /**
     * Kernel constructor.
     * Enables the exception handler, defines the project
     * root directory, takes the incoming request
     * and fires up the application.
     *
     * @throws Exceptions\Core\Configuration\ConfigurationFileNotFoundException
     * @throws Exceptions\Core\Configuration\ConfigurationFileNotYmlException
     * @throws Exceptions\Core\Routing\ActionNotFoundException
     * @throws Exceptions\Core\Routing\ControllerNotFoundException
     * @throws Exceptions\Core\Routing\NoRoutesFoundException
     * @throws ReflectionException
     */
    public function __construct ()
    {
        set_exception_handler([$this, 'exception_handler']);

        $router = new Router();
        $http = new Http();
        $dotEnv = new Dotenv();

        $reflection = new \ReflectionClass(ClassLoader::class);

        define("PROJECT_DIR", dirname($reflection->getFileName(), 3));

        $dotEnv->load(PROJECT_DIR . '/.env');

        $method = $http->retrieveRequestedPath()['method'];
        $uri = $http->retrieveRequestedPath()['uri'];
        $router->enableRouting($uri, $method);
    }

    /**
     * @param Throwable $e
     */
    public function exception_handler (Throwable $e)
    {
        echo "<pre>";

        print_r($e->getMessage() . "\n");
        print_r($e->getFile() . "\n");
        print_r($e->getLine() . "\n\n");

        print_r($e->getPrevious());

        echo "</pre>";
    }
}

