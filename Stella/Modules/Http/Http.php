<?php


namespace Stella\Modules\Http;

use Stella\Exceptions\Core\Configuration\{ConfigurationFileNotFoundException, ConfigurationFileNotYmlException};
use Stella\Core\Configuration;

/**
 * -----------------------------------------
 * Class Http
 * -----------------------------------------
 *
 * Handles connection with the web client
 *
 * @author Benjamin Gil Flores
 * @version NaN
 * @package Stella\Core
 */
class Http
{
    /**
     * @var Configuration Holds a Configuration class instance
     */
    private Configuration $configuration;

    /**
     * Http constructor.
     */
    public function __construct()
    {
        $this->configuration = new Configuration();
    }

    /**
     * Retrieves requested URI and HTTP Method
     *
     * @return array
     * @throws ConfigurationFileNotFoundException
     * @throws ConfigurationFileNotYmlException
     */
    public function retrieveRequestedPath () : array
    {
        $serverConf = $this->configuration->getConfigurationOfFile("./config/server.yml");
        $uri = isset($serverConf['web_root']) ?
            str_replace($serverConf['web_root'], "", $_SERVER['REQUEST_URI']) :
            $_SERVER['REQUEST_URI'];

        return array(
            'uri' => $uri,
            'method' => $_SERVER['REQUEST_METHOD']
        );
    }

    public function getRequestData (int $dataType) : array
    {
        switch ($dataType) {
            case 0:
                return $_GET;
            case 1:
                return $_POST;
            case 3:
            case 4:
                return file_get_contents("php:://input");
        }
    }
}