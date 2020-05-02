<?php


namespace Stella\Core;


use Stella\Exceptions\Core\Configuration\ConfigurationFileNotFoundException;
use Stella\Exceptions\Core\Configuration\ConfigurationRoutesDirectoryNotFoundException;
use Symfony\Component\Yaml\Yaml;

/**
 * Class Configuration
 *
 * Reads configuration of application
 *
 * @author Benjamin Gil Flores
 * @version 0.3
 * @since 0.1
 * @package Stella\Core
 */
class Configuration
{
    /**
     * Reads yml of given configuration package
     * and parses it to an array.
     *
     * @param string $configName
     * @return array
     * @throws ConfigurationFileNotFoundException
     */
    public function getConfigurationOfFile (string $configName) : array
    {
        $projectDir = defined('PROJECT_DIR') ? PROJECT_DIR : PROJECT_DIR_CLI;

        $configFile = $projectDir . '/config/config.yml';

        if (!file_exists($configFile)) {
            throw new ConfigurationFileNotFoundException("The configuration file was not found");
        }

        $configuration = Yaml::parseFile($configFile);

        if (isset($configuration[$configName])) {
            $this->replaceEnvVariablesInFile($configuration[$configName]);
        }

        return isset($configuration[$configName]) ? $configuration[$configName] : array();
    }

    /**
     * Reads all yml files found in routes directory
     * and parses their content into one routes array.
     *
     * @return array
     * @throws ConfigurationRoutesDirectoryNotFoundException
     */
    public function getRoutesOutOfConfigurationFiles () : array
    {
        $routes = array();
        $routesDirPath = PROJECT_DIR . '/config/routes/';

        if (!is_dir($routesDirPath)) {
            throw new ConfigurationRoutesDirectoryNotFoundException("The routes directory was not found");
        }

        $routeFiles = scandir($routesDirPath, 1);

        foreach ($routeFiles as $routeFile) {
            $fileExtension = pathinfo($routesDirPath . $routeFile)['extension'];

            if ($fileExtension === 'yml') {
                $currentRouteFileArray = Yaml::parseFile($routesDirPath . $routeFile);

                foreach ($currentRouteFileArray as $key => $value) {
                    $routes[$key] = $value;
                }
            }
        }

        return $routes;
    }

    /**
     * Walks through the a yaml file array and replaces the env
     * key value with the actual environment variable
     *
     * @param array $yamlArray
     */
    private function replaceEnvVariablesInFile (array &$yamlArray) : void
    {
        array_walk_recursive($yamlArray, function (&$item) {
            // The value of the element must start with 'env('
            if (substr($item, 0, 4) === 'env(') {
                // ENV Key must be wrapper in parenthesis
                preg_match('#\((.*?)\)#', $item, $match);

                // Sets the actual env value
                if (isset($match[1])) {
                    $item = $_ENV[$match[1]];
                }
            }
        });
    }
}

