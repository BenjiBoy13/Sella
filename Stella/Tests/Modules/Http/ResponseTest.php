<?php

namespace Stella\Tests\Modules\Http;

use PHPUnit\Framework\TestCase;
use Stella\Modules\Http\Response;

class ResponseTest extends TestCase
{
    private Response $response;

    public function setUp(): void
    {
        $this->response = new Response();
    }

    public function test_valid_twig_view ()
    {
        $twigLoaded = $this->response->renderView("@Stella/tests/template-test.html.twig");
        $this->assertEquals(true, $twigLoaded);
    }

    public function test_twig_custom_exception ()
    {
        $this->expectException(\Stella\Exceptions\Modules\Http\TwigRenderException::class);
        $this->expectExceptionMessage("Could not load twig template base/base.html.twig");
        $this->response->renderView("base/base.html.twig");
    }

    public function test_invalid_http_code ()
    {
        $this->expectException(\Stella\Exceptions\Modules\Http\InvalidHttpResponseCode::class);
        $this->expectExceptionMessage("The http 678 is invalid");
        $this->response->setResponseCode(678);
    }
}