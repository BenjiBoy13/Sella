<?php


namespace Stella\Modules\Terminal;

/**
 * -----------------------------------------
 * Class TerminalOutput
 * -----------------------------------------
 *
 * Manages the output streams send back to
 * terminal
 *
 * @author Benjamin Gil Flores
 * @version NaN
 * @package Stella\Modules\Terminal
 */
class TerminalOutput
{
    /**
     * @var string
     */
    private string $format;

    /**
     * TerminalOutput constructor.
     */
    public function __construct()
    {
        $this->format = "";
    }

    /**
     * Sets the format of the output stream,
     * (warning, danger, fatal, success)
     *
     * @param string $format
     * @return $this
     */
    public function setFormat (string $format): self
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Outputs the stream back to the terminal with
     * format applied
     *
     * @param string $message
     */
    public function outputMessage (string $message)
    {
        echo ucfirst($this->format) . ": $message";
    }
}