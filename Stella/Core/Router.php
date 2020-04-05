<?php


namespace Stella\Core;


use Stella\Controllers\StellaController;
use Stella\Exceptions\Core\Configuration\{ConfigurationFileNotYmlException, ConfigurationFileNotFoundException};
use Stella\Exceptions\Core\Routing\{ActionNotFoundException, ControllerNotFoundException, NoRoutesFoundException};

/**
 * -----------------------------------------
 * Class Router
 * -----------------------------------------
 *
 * Connects requested URI and Http Method to proper
 * controller if the requested URI matches one of the
 * routes defined in the routes configuration directory;
 * In case there is no matches a NotFound Exception
 * will be thrown.
 *
 * @author Benjamin Gil FLores
 * @version NaN
 * @package Stella\Core
 */
class Router
{
    /**
     * @var Configuration Holds a Configuration class instance
     */
    private Configuration $configuration;

    /**
     * Router constructor.
     */
    public function __construct()
    {
        $this->configuration = new Configuration();
    }

    /**
     * Renders the controller for the requested URI
     * if a match is met.
     *
     * @param string $requestedPath
     * @param string $requestedMethod
     * @param bool $testRoutes
     * @return bool
     * @throws ActionNotFoundException
     * @throws ConfigurationFileNotFoundException
     * @throws ConfigurationFileNotYmlException
     * @throws ControllerNotFoundException
     * @throws NoRoutesFoundException
     */
    public function enableRouting (string $requestedPath, string $requestedMethod, bool $testRoutes = false) : bool
    {
        if ($testRoutes) {
            $routes = $this->configuration->getRoutesOutOfConfigurationFiles( dirname(__DIR__, 1) . "/config/tests/routes/");
        } else {
            $routes = $this->configuration->getRoutesOutOfConfigurationFiles(PROJECT_DIR . '/config/routes/');
        }

        // Verifying that there are routes defined in the configuration files
        if (empty($routes)) {
            throw new NoRoutesFoundException("No routes found in routes directory");
        }

        // Looping to all expected routes
        foreach ($routes as $route)
        {
            foreach ($route['paths'] as $key => $path) {
                if ($path['method'] === $requestedMethod) {
                    $path['path'] = isset($route['prefix']) ? $route['prefix'] . $path['path'] : $path['path'];

                    // Check if there is a match in current iteration
                    $match = $this->checkIfMatch($path['path'], $requestedPath);
                    if ($match !== null) {
                        // Check if controller exists
                        if (!class_exists($route['controller'])) {
                            throw new ControllerNotFoundException("Controller not found in " . $route['controller']);
                        }

                        $controller = new $route['controller'];

                        // Check if action is found on controller
                        if (!method_exists($route['controller'], $path['action'])) {
                            throw new ActionNotFoundException("Method " . $path['action'] . " not found in " . $route['controller'] . " controller");
                        }

                        // Checking if the controller is a valid stella controller
                        if (!is_subclass_of($controller, StellaController::class)) {
                            throw new ControllerNotFoundException("Controller file found but is not a valid controller, expects to extend from StellaController");
                        }

                        $arguments = array();

                        // Instantiating services for action if required.
                        if (isset($path['services'])) {
                            $this->instantiateServices($arguments, $path['services']);
                        }

                        array_push($arguments, $match);

                        // Call controller action with parameters
                        call_user_func_array([$controller, $path['action']], $arguments);
                        return true;
                    }
                }
            }
        }

        // No matches found.
        throw new NoRoutesFoundException("404, No route was found for requested URI");
    }

    /**
     * Check if a route configuration path matches the
     * requested URI.
     *
     * @param string $path
     * @param string $requestedUri
     * @return array|null
     */
    private function checkIfMatch (string $path, string $requestedUri) : ?array
    {
        // Removing last forward slash and get params
        $requestedUri = strtok($requestedUri, "?");

        if (substr($requestedUri, -1) == '/' && $requestedUri !== "/") {
            $requestedUri = substr($requestedUri, 0, '-1');
        }

        // Fragmenting paths
        $pathFragments = explode("/", $path);
        $requestedUriFragments = explode("/", $requestedUri);
        $matchArray = array();

        array_shift($pathFragments);
        array_shift($requestedUriFragments);

        // Validation for matching
        if (sizeof($pathFragments) === sizeof($requestedUriFragments)) {
            for ($i = 0; $i < sizeof($pathFragments); $i++) {
                if ($pathFragments[$i] != $requestedUriFragments[$i]) {
                    // Check if the non matching fragment is a dynamic variable
                    if ($this->checkIfParameter($pathFragments[$i])) {
                        array_push($matchArray, $requestedUriFragments[$i]);
                    } else {
                        return null;
                    }
                }
            }

            return $matchArray;
        }

        return null;
    }

    /**
     * Check if a path fragment is a dynamic variable
     *
     * @param string $pathFragment
     * @return bool
     */
    private function checkIfParameter (string $pathFragment) : bool
    {
        if (preg_match("#\((.*?)\)#", $pathFragment, $match)) {
            return ($match[0] === "(var)") ? true : false;
        }

        return false;
    }

    /**
     * Instantiate the requested services and returns them
     * into an array.
     *
     * @param array $arguments
     * @param array $services
     */
    private function instantiateServices (array &$arguments, array $services)
    {
        foreach ($services as $service) {
            if (class_exists($service)) {
                array_push($arguments, new $service);
            }
        }
    }
}