<?php


namespace Stella\Tests\Core;


use PHPUnit\Framework\TestCase;
use Stella\Core\Configuration;
use Stella\Exceptions\Core\Configuration\ConfigurationFileNotFoundException;
use Stella\Exceptions\Core\Configuration\ConfigurationFileNotYmlException;
use Symfony\Component\Dotenv\Dotenv;

class ConfigurationTest extends TestCase
{
    private Configuration $configuration;

    public function setUp(): void
    {
        $this->configuration = new Configuration();

        $dotenv = new Dotenv();
        $dotenv->load( dirname(__DIR__, 2) . "/.env.test");
    }

    public function test_if_routes_are_correctly_injected_in_array ()
    {
        $expectedArray = $this->configuration->getConfigurationOfFile( dirname(__DIR__, 2) . "/config/tests/routes_test.yml");

        $routesArray = $this->configuration->getRoutesOutOfConfigurationFiles(dirname(__DIR__, 2) . "/config/tests/routes/");

        $this->assertEquals($expectedArray, $routesArray);
    }

    public function test_configuration_file_not_found_exception ()
    {
        $this->expectException(ConfigurationFileNotFoundException::class);
        $this->configuration->getConfigurationOfFile(dirname(__DIR__, 2) . "/config/nonexistent.yml");
    }

    public function test_configuration_file_not_yml ()
    {
        $this->expectException(ConfigurationFileNotYmlException::class);
        $this->configuration->getConfigurationOfFile(dirname(__DIR__, 2) . "/config/tests/text.txt");
    }

    public function test_env_variables_in_yml_files ()
    {
        $expectedArray = array(
            'value' => array(
                'other_value' => 1,
                'x' => array(
                    2,
                ),
                'z' => 2
            ),
            'w' => "spaguetti"
        );

        $this->assertEquals($expectedArray,
            $this->configuration->getConfigurationOfFile(dirname(__DIR__, 2) . '/config/tests/env_test.yml')
        );
    }

    public function test_array_formatting ()
    {
        $expectedArray = array(
            'namespaces' => array(
                array(
                    'name' => 'app',
                    'directory' => '/path/to/file'
                )
            ),
            'extensions' => array(
                'App\Extensions\ClassName'
            )
        );

        $this->assertEquals($expectedArray,
            $this->configuration->getConfigurationOfFile(dirname(__DIR__, 2) . '/config/tests/twig_test.yml'));
    }
}