<?php


namespace Stella\Modules\Twig;


use Stella\Core\Configuration;
use Stella\Exceptions\Core\Configuration\ConfigurationFileNotFoundException;
use Stella\Exceptions\Modules\Twig\CustomTwigExtensionNotFoundException;
use Stella\Exceptions\Modules\Twig\DirectoryDoesNotExistException;
use Stella\Modules\Twig\Extensions\StellaTwigFilters;
use Stella\Modules\Twig\Extensions\StellaTwigFunctions;
use Stella\Modules\Twig\Extensions\StellaTwigGlobals;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Extension\AbstractExtension;
use Twig\Loader\FilesystemLoader;

/**
 * Class StellaTwigLoader
 *
 * @author Benjamin Gil Flores
 * @version 0.3
 * @since 0.1
 * @package Stella\Modules\Twig
 */
class StellaTwigLoader
{
    /**
     * @var Environment
     */
    private Environment $twigEnvironment;

    /**
     * @var FilesystemLoader
     */
    private FilesystemLoader $twigLoader;

    /**
     * @var Configuration
     */
    private Configuration $configuration;

    /**
     * StellaTwigLoader constructor.
     * @throws LoaderError
     */
    public function __construct()
    {
        $this->twigLoader = new FilesystemLoader();
        $this->twigEnvironment = new Environment($this->twigLoader);
        $this->configuration = new Configuration();

        // Declaring Stella custom extensions
        $this->twigEnvironment->addExtension(new StellaTwigGlobals());
        $this->twigEnvironment->addExtension(new StellaTwigFilters());
        $this->twigEnvironment->addExtension(new StellaTwigFunctions());

        // Declaring stella templates namespace
        $this->twigLoader->addPath( dirname(__DIR__, 2) . "/Resources/templates", 'Stella');
    }

    /**
     * @return Environment
     */
    public function getTwigEnvironment () : Environment
    {
        return $this->twigEnvironment;
    }

    /**
     * @param string $directory
     * @param string $namespaceName
     * @return bool
     * @throws LoaderError
     * @throws DirectoryDoesNotExistException
     */
    public function addTwigNamespace (string $directory, string $namespaceName) : bool
    {
        if (file_exists($directory) && is_dir($directory)) {
            $this->twigLoader->addPath($directory, $namespaceName);
            return true;
        }

        throw new DirectoryDoesNotExistException("Twig could not find directory $directory");
    }

    /**
     * @param string $class
     * @return bool
     * @throws CustomTwigExtensionNotFoundException
     */
    public function addTwigCustomExtension (string $class) : bool
    {
        if (!class_exists($class)) {
            throw new CustomTwigExtensionNotFoundException("The class $class was not found");
        }

        $customExtension = new $class;

        if (!is_subclass_of($customExtension, AbstractExtension::class)) {
            throw new CustomTwigExtensionNotFoundException("The class $class was found but is not valid");
        }

        $this->twigEnvironment->addExtension($customExtension);
        return true;
    }

    /**
     * @return $this
     * @throws CustomTwigExtensionNotFoundException
     * @throws DirectoryDoesNotExistException
     * @throws LoaderError
     * @throws ConfigurationFileNotFoundException
     */
    public function loadTwigConf () : self
    {
        $twigConfig = $this->configuration->getConfigurationOfFile('twig');

        // Declaring custom namespaces
        if (isset($twigConfig['namespaces'])) {
            foreach ($twigConfig['namespaces'] as $namespace) {
                if (isset($namespace['directory']) && isset($namespace['name'])) {
                    $this->addTwigNamespace(PROJECT_DIR . $namespace['directory'], $namespace['name']);
                }
            }
        }

        // Declaring custom extensions
        if (isset($twigConfig['extensions'])) {
            foreach ($twigConfig['extensions'] as $extension) {
                $this->addTwigCustomExtension($extension);
            }
        }

        return $this;
    }
}