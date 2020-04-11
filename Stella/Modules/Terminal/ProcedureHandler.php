<?php


namespace Stella\Modules\Terminal;

use Stella\Modules\Terminal\Procedure\StellaProcedureInterface;

/**
 * -----------------------------------------
 * Class ProcedureHandler
 * -----------------------------------------
 *
 * @author Benjamin Gil Flores
 * @version NaN
 * @package Stella\Modules\Terminal
 */
class ProcedureHandler
{
    /**
     * @var TerminalOutput
     */
    private TerminalOutput $terminalOutput;

    /**
     * @var StellaProcedureInterface
     */
    private StellaProcedureInterface $procedure;

    /**
     * @var string
     */
    private string $action;

    /**
     * @var array
     */
    private array $procedureResponse;

    /**
     * ProcedureHandler constructor.
     *
     * @param StellaProcedureInterface $procedure
     * @param string $action
     */
    public function __construct (StellaProcedureInterface $procedure, string $action)
    {
        $this->procedure = $procedure;
        $this->terminalOutput = new TerminalOutput();
        $this->action = $action;
    }

    /**
     * Launches the procedure
     *
     * @return $this
     */
    public function initiateProcedure (): self
    {
        $this->procedureResponse = $this->procedure->setOptions()->setAction($this->action)->do();
        return $this;
    }

    /**
     * Logs the procedure response to the console
     */
    public function logProcedureResponse ()
    {
        $format = $this->procedureResponse['format'];
        $message = $this->procedureResponse['message'];

        $this->terminalOutput->setFormat($format)->outputMessage($message);
    }
}