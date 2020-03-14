<?php


namespace Stella\Controllers;


use Stella\Modules\Http\Http;
use Stella\Modules\Http\Response;

class StellaController
{
    protected Response $response;
    protected Http $http;

    public function __construct()
    {
        $this->response = new Response();
        $this->http = new Http();
    }
}