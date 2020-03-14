<?php


namespace App\Controllers;


use Stella\Controllers\StellaController;
use Stella\Exceptions\Modules\Http\InvalidHttpResponseCode;
use Stella\Services\ExampleService;

class BooksController extends StellaController
{
    /**
     * @param ExampleService $exampleService
     * @param array $params
     * @return bool
     * @throws InvalidHttpResponseCode
     */
    public function showAction (ExampleService $exampleService, array $params)
    {
        $this->response->setResponseCode(202);
        $data = $this->http->getRequestData(1);

        return $this->response->renderJson(array(
            'type' => 'I dont know'
        ));
    }
}