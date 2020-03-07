<?php


namespace Stella\Core;


use PHPUnit\Framework\TestCase;
use Stella\Exceptions\Core\Configuration\ConfigurationFileNotFoundException;
use Stella\Exceptions\Core\Configuration\ConfigurationFileNotYmlException;

class ConfigurationTest extends TestCase
{
    private Configuration $configuration;

    public function setUp(): void
    {
        $this->configuration = new Configuration();
    }

    public function test_if_routes_are_correctly_injected_in_array ()
    {
        $expectedArray = $this->configuration->getConfigurationOfFile("./config/tests/routes_test.yml");

        $routesArray = $this->configuration->getRoutesOutOfConfigurationFiles();

        $this->assertEquals($expectedArray, $routesArray);
    }

    public function test_configuration_file_is_giving_expected_array ()
    {
        $expectedServerArray = array(
            'debug' => true,
            'app_name' => 'appname',
            'web_root' => '/stella'
        );

        $serverFileArray = $this->configuration->getConfigurationOfFile("./config/server.yml");

        $this->assertEquals($expectedServerArray, $serverFileArray);
    }

    public function test_configuration_file_not_found_exception ()
    {
        $this->expectException(ConfigurationFileNotFoundException::class);
        $this->expectExceptionMessage("The configuration file ./config/nonexistent.yml was not found");
        $this->configuration->getConfigurationOfFile("./config/nonexistent.yml");
    }

    public function test_configuration_file_not_yml ()
    {
        $this->expectException(ConfigurationFileNotYmlException::class);
        $this->expectExceptionMessage("The configuration file ./config/text.txt is not a yml file");
        $this->configuration->getConfigurationOfFile("./config/text.txt");
    }
}