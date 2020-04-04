<?php


namespace Stella\Modules\Http;

use Stella\Exceptions\Core\Configuration\{ConfigurationFileNotFoundException, ConfigurationFileNotYmlException};
use Stella\Core\Configuration;
use Stella\Exceptions\Modules\Http\InvalidRequestDataModeException;

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
     * @var array
     */
    private array $requestData = array();

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

    /**
     * @param int $mode
     * @return $this
     * @throws InvalidRequestDataModeException
     */
    public function setRequestDataMode (int $mode) : self
    {
        if ($mode === 1) {
            $this->requestData = $_POST;
        } else if ($mode === 2) {
            $this->requestData = $_GET;
        } else {
            throw new InvalidRequestDataModeException("The mode $mode is not valid, try 1|2");
        }

        return $this;
    }

    public function sanitize () : self
    {
        if (!empty($this->requestData)) {
            array_walk_recursive($this->requestData, [$this, 'sanitizeValue']);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getRequestData () : array
    {
        return $this->requestData;
    }

    private function sanitizeValue (&$value)
    {
        $value = trim($value);
        $value = preg_replace("/\s+/", " ", $value);
        $value = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $value);
        $value = preg_replace("/^<\?php(.*)(\?>)?$/s", '', $value);
    }
}