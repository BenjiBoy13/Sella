<?php


namespace Stella\Tests\Modules\Twig;


use PHPUnit\Framework\TestCase;
use Stella\Exceptions\Modules\Twig\CustomTwigExtensionNotFoundException;
use Stella\Exceptions\Modules\Twig\DirectoryDoesNotExistException;
use Stella\Modules\Twig\Extensions\Tests\TestingTwigExtensionOne;
use Stella\Modules\Twig\Extensions\Tests\TestingTwigExtensionTwo;
use Stella\Modules\Twig\StellaTwigLoader;

class StellaTwigLoaderTest extends TestCase
{
    private StellaTwigLoader $stellaTwigLoader;

    public function setUp(): void
    {
        $this->stellaTwigLoader = new StellaTwigLoader();
    }

    public function test_add_new_twig_namespace_when_directory_does_not_exist ()
    {
        $this->expectException(DirectoryDoesNotExistException::class);
        $this->expectExceptionMessage("Twig could not find directory non-existent/dir");
        $this->stellaTwigLoader->addTwigNamespace('non-existent/dir', 'NotExistent');
    }

    public function test_add_new_twig_namespace_when_dir_exists ()
    {
        $this->assertEquals(true,
            $this->stellaTwigLoader
                ->addTwigNamespace(dirname(__DIR__, 3) . '/Resources/tests', "StellaTwig"));
    }

    public function test_add_new_custom_twig_extension_when_class_does_not_exist ()
    {
        $this->expectException(CustomTwigExtensionNotFoundException::class);
        $this->expectExceptionMessage("The class NoNamespace/CustomExtension was not found");
        $this->stellaTwigLoader->addTwigCustomExtension("NoNamespace/CustomExtension");
    }

    public function test_add_new_twig_extension_when_invalid ()
    {
        $this->expectException(CustomTwigExtensionNotFoundException::class);
        $this->expectExceptionMessage(
            "The class Stella\Modules\Twig\Extensions\Tests\TestingTwigExtensionOne was found but is not valid"
        );

        $this->stellaTwigLoader->addTwigCustomExtension(TestingTwigExtensionOne::class);
    }

    public function test_add_new_twig_extension_when_valid ()
    {
        $this->assertEquals(true,
            $this->stellaTwigLoader->addTwigCustomExtension(TestingTwigExtensionTwo::class)
        );
    }
}