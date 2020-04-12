<?php


namespace Stella\Modules\Http;


use Stella\Core\Configuration;
use Stella\Exceptions\Core\Configuration\ConfigurationFileNotFoundException;
use Stella\Exceptions\Core\Configuration\ConfigurationFileNotYmlException;
use Stella\Exceptions\Modules\Http\InvalidHttpResponseCode;
use Stella\Exceptions\Modules\Http\TwigRenderException;
use Stella\Exceptions\Modules\Twig\CustomTwigExtensionNotFoundException;
use Stella\Exceptions\Modules\Twig\DirectoryDoesNotExistException;
use Stella\Modules\Twig\StellaTwigLoader;
use Twig\Error\Error;
use Twig\Error\LoaderError;

/**
 * Class Response
 *
 * Handles the making of the response for the
 * client including headers and http status
 * code; Response can be JSON formatted or
 * an html view powered by the twig engine
 *
 * @author Benjamin Gil Flores
 * @version 0.1
 * @package Stella\Modules\Http
 */
class Response
{
    /**
     * @var StellaTwigLoader
     */
    protected StellaTwigLoader $stellaTwig;

    /**
     * @var Configuration
     */
    protected Configuration $configuration;

    /**
     * Response constructor.
     */
    public function __construct()
    {
        $this->stellaTwig = new StellaTwigLoader();
        $this->configuration = new Configuration();
    }

    /**
     * Renders an html template powered by twig if
     * possible
     *
     * @param string $templatePath
     * @param array $data
     * @return bool
     * @throws ConfigurationFileNotFoundException
     * @throws ConfigurationFileNotYmlException
     * @throws TwigRenderException
     * @throws CustomTwigExtensionNotFoundException
     * @throws DirectoryDoesNotExistException
     * @throws LoaderError
     */
    public function renderView (string $templatePath, array $data = array()) : bool
    {
        $twig = $this->stellaTwig->loadTwigConf()->getTwigEnvironment();

        try {
            echo $twig->render($templatePath, $data);
        } catch (Error $e) {
            throw new TwigRenderException("Could not load twig template $templatePath", null, $e);
        }

        return true;
    }

    /**
     * Renders a JSON response
     *
     * @param array $data
     * @return bool
     */
    public function renderJson (array $data) : bool
    {
        echo json_encode($data);
        return true;
    }

    /**
     * Sets the http response code
     *
     * @param int $code
     * @throws InvalidHttpResponseCode if the http code is not on valid list
     */
    final public function setResponseCode(int $code) : void
    {
        $validHttpCodes = [
            100, 101, 200, 201, 202, 204, 206, 207, 300, 301, 302, 303, 304, 305, 307, 400,
            401, 402, 403, 404, 405, 406, 408, 409, 410, 411, 412, 413, 414, 415, 416, 417,
            418, 420, 421, 422, 423, 424, 425, 426, 429, 431, 444, 450, 451, 499, 500, 501,
            502, 503, 504, 506, 507, 508, 509, 510, 511, 599
        ];

        if (!in_array($code, $validHttpCodes)) {
            throw new InvalidHttpResponseCode("The http $code is invalid");
        }

        http_response_code($code);
    }
}