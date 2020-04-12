<?php

namespace Stella;

use Composer\Autoload\ClassLoader;
use ReflectionException;
use Stella\Core\Router;
use Stella\Modules\Http\Http;
use Symfony\Component\Dotenv\Dotenv;
use Throwable;
use Stella\Exceptions\Core\Configuration\ConfigurationFileNotFoundException;
use Stella\Exceptions\Core\Configuration\ConfigurationFileNotYmlException;
use Stella\Exceptions\Core\Routing\ActionNotFoundException;
use Stella\Exceptions\Core\Routing\ControllerNotFoundException;
use Stella\Exceptions\Core\Routing\NoRoutesFoundException;

/**
 * Class Kernel
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
 * @version 0.1
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
     * @throws ConfigurationFileNotFoundException
     * @throws ConfigurationFileNotYmlException
     * @throws ActionNotFoundException
     * @throws ControllerNotFoundException
     * @throws NoRoutesFoundException
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
     * Catches all exceptions and logs them in
     * view
     *
     * @param Throwable $e
     */
    public function exception_handler (Throwable $e)
    {
        // TODO: render error in twig template
        echo "<pre>";

        print_r($e->getMessage() . "\n");
        print_r($e->getFile() . "\n");
        print_r($e->getLine() . "\n\n");

        print_r($e->getPrevious());

        echo "</pre>";
    }
}

