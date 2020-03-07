<?php


namespace Stella\Core;


use Stella\Exceptions\Core\Configuration\ConfigurationFileNotFoundException;
use Stella\Exceptions\Core\Configuration\ConfigurationFileNotYmlException;
use Symfony\Component\Yaml\Yaml;

/**
 * -----------------------------------------
 * Class Configuration
 * -----------------------------------------
 *
 * Reads configuration of application
 *
 * @author Benjamin Gil Flores
 * @version NaN
 * @package Stella\Core
 */
class Configuration
{
    /**
     * Reads yml out of given configuration file
     * and parses it to an array.
     *
     * @param string $filePath
     * @return array
     * @throws ConfigurationFileNotFoundException
     * @throws ConfigurationFileNotYmlException
     */
    public function getConfigurationOfFile (string $filePath) : array
    {
        if (!file_exists($filePath)) {
            throw new ConfigurationFileNotFoundException("The configuration file $filePath was not found");
        }

        $fileExtension = pathinfo($filePath)['extension'];

        if ($fileExtension !== 'yml') {
            throw new ConfigurationFileNotYmlException("The configuration file $filePath is not a yml file");
        }

        $configurationVariablesArray = Yaml::parseFile($filePath);

        return $configurationVariablesArray ? $configurationVariablesArray : array();
    }

    /**
     * Reads all yml files found in routes directory
     * and parses their content into one routes array.
     *
     * @return array
     * @throws ConfigurationFileNotFoundException
     * @throws ConfigurationFileNotYmlException
     */
    public function getRoutesOutOfConfigurationFiles () : array
    {
        $routes = array();

        $routeFiles = scandir("./config/routes", 1);

        foreach ($routeFiles as $routeFile) {
            $fileExtension = pathinfo("./config/routes/$routeFile")['extension'];

            if ($fileExtension === 'yml') {
                $currentRouteFileArray = $this->getConfigurationOfFile("./config/routes/$routeFile");

                foreach ($currentRouteFileArray as $key => $value) {
                    $routes[$key] = $value;
                }
            }
        }

        return $routes;
    }
}

