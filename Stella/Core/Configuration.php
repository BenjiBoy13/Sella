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

        $this->replaceEnvVariablesInFile($configurationVariablesArray);

        return $configurationVariablesArray ? $configurationVariablesArray : array();
    }

    /**
     * Reads all yml files found in routes directory
     * and parses their content into one routes array.
     *
     * @param string $routesDirPath
     * @return array
     * @throws ConfigurationFileNotFoundException
     * @throws ConfigurationFileNotYmlException
     */
    public function getRoutesOutOfConfigurationFiles (string $routesDirPath = "./config/routes") : array
    {
        $routes = array();

        $routeFiles = scandir($routesDirPath, 1);

        foreach ($routeFiles as $routeFile) {
            $fileExtension = pathinfo($routesDirPath . $routeFile)['extension'];

            if ($fileExtension === 'yml') {
                $currentRouteFileArray = $this->getConfigurationOfFile($routesDirPath . $routeFile);

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

