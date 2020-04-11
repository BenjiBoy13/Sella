<?php


namespace Stella\Modules\Terminal\Procedure;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Stella\Core\Configuration;

/**
 * -----------------------------------------
 * Abstract Class Procedure
 * -----------------------------------------
 *
 * Does an procedure for any given
 * command, usually changing or creating new
 * architecture for the application
 *
 * @see StellaProcedureInterface
 *
 * @author Benjamin Gil Flores
 * @version NaN
 * @package Stella\Modules\Terminal\Procedure
 */
abstract class Procedure implements StellaProcedureInterface
{
    /**
     * @var array
     */
    protected array $options;

    /**
     * @var string
     */
    protected string $action;

    /**
     * @var Configuration
     */
    protected Configuration $configuration;

    /**
     * AssetsProcedure constructor.
     */
    public function __construct()
    {
        $this->configuration = new Configuration();
    }

    /**
     * Takes the options of the executed command
     * and orders them
     *
     * @return $this
     */
    abstract function setOptions (): self;

    /**
     * Sets the action of the procedure
     *
     * @param string $action
     * @return $this
     */
    function setAction (string $action): self
    {
        $this->action = $action;

        return $this;
    }


    /**
     * Executes the procedure
     *
     * @return array
     * @throws ReflectionException
     */
    public function do (): array
    {
        // Find if the action exists
        if (method_exists($this, $this->action)) {
            // Return action response
            $reflectionFunction = new ReflectionMethod($this, $this->action);
            return ($reflectionFunction->getReturnType() == "array") ?
                call_user_func([$this, $this->action]) :
                array();
        }

        print "Error: The action '" . $this->action . "' was not found in the procedure " . $this->__toString();
        exit;
    }

    /**
     * Returns name of the class with namespace
     *
     * @throws ReflectionException
     */
    public function __toString(): string
    {
        $reflectionClass = new ReflectionClass($this);
        return $reflectionClass->getName();
    }
}