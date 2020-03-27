<?php


namespace Stella\Tests\Core;


use PHPUnit\Framework\TestCase;
use Stella\Core\Configuration;
use Stella\Exceptions\Core\Configuration\ConfigurationFileNotFoundException;
use Stella\Exceptions\Core\Configuration\ConfigurationFileNotYmlException;
use Symfony\Component\Dotenv\Dotenv;

require_once '/Users/benjamin_gil/Sites/stella/Stella/constants.php';

class ConfigurationTest extends TestCase
{
    private Configuration $configuration;

    public function setUp(): void
    {
        $this->configuration = new Configuration();

        $dotenv = new Dotenv();
        $dotenv->load(STELLA_ROOT . "/.env.test");
    }

    public function test_if_routes_are_correctly_injected_in_array ()
    {
        $expectedArray = $this->configuration->getConfigurationOfFile(STELLA_ROOT . "/config/tests/routes_test.yml");

        $routesArray = $this->configuration->getRoutesOutOfConfigurationFiles(STELLA_ROOT . "/config/tests/routes/");

        $this->assertEquals($expectedArray, $routesArray);
    }

    public function test_configuration_file_not_found_exception ()
    {
        $this->expectException(ConfigurationFileNotFoundException::class);
        $this->expectExceptionMessage("The configuration file " . STELLA_ROOT . "/config/nonexistent.yml was not found");
        $this->configuration->getConfigurationOfFile(STELLA_ROOT . "/config/nonexistent.yml");
    }

    public function test_configuration_file_not_yml ()
    {
        $this->expectException(ConfigurationFileNotYmlException::class);
        $this->expectExceptionMessage("The configuration file " . STELLA_ROOT . "/config/tests/text.txt is not a yml file");
        $this->configuration->getConfigurationOfFile(STELLA_ROOT . "/config/tests/text.txt");
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
            $this->configuration->getConfigurationOfFile(STELLA_ROOT . '/config/tests/env_test.yml')
        );
    }
}