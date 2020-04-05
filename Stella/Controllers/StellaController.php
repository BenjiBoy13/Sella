<?php


namespace Stella\Controllers;


use Stella\Modules\Http\Http;
use Stella\Modules\Http\Response;

/**
 * -----------------------------------------
 * Class StellaController
 * -----------------------------------------
 *
 * Controller base class, instantiates response
 * and http classes
 *
 * @author Benjamin Gil Flores
 * @version NaN
 * @package Stella\Controllers
 */
class StellaController
{
    /**
     * @var Response
     */
    protected Response $response;

    /**
     * @var Http
     */
    protected Http $http;

    /**
     * StellaController constructor.
     */
    public function __construct()
    {
        $this->response = new Response();
        $this->http = new Http();
    }
}