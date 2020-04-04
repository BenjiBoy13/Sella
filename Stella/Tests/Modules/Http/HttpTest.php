<?php


namespace Stella\Tests\Modules\Http;


use PHPUnit\Framework\TestCase;
use Stella\Exceptions\Modules\Http\InvalidRequestDataModeException;
use Stella\Modules\Http\Http;

class HttpTest extends TestCase
{
    protected Http $http;

    public function setUp(): void
    {
        $this->http = new Http();

        $_POST = [
            '1' => "<script> console.log('Hacked') </script>hacked",
            '2' => " Check for    trim  ",
            '3' => "<p> Normal html </p>",
            '4' => "<?php echo 'hacked' ?>"
        ];

        $_GET = [
            '1' => 'test'
        ];
    }

    public function test_getting_request_data_type_post_no_sanitize ()
    {
        $this->assertEquals($_POST, $this->http->setRequestDataMode(1)->getRequestData());

        $this->assertEquals($_GET, $this->http->setRequestDataMode(2)->getRequestData());
    }

    public function test_sanitize_values_from_request_data ()
    {
        $this->assertEquals(array(
            '1' => "hacked",
            '2' =>  "Check for trim",
            '3' => "<p> Normal html </p>",
            '4' => ""
        ), $this->http->setRequestDataMode(1)->sanitize()->getRequestData());
    }

    public function test_invalid_request_mode_exception ()
    {
        $this->expectException(InvalidRequestDataModeException::class);
        $this->expectExceptionMessage("The mode 4 is not valid, try 1|2");
        $this->assertEquals($_POST, $this->http->setRequestDataMode(4)->getRequestData());
    }
}