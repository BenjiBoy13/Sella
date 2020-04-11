<?php


namespace Stella\Modules\Terminal\Procedure;

/**
 * -----------------------------------------
 * Interface StellaProcedureInterface
 * -----------------------------------------
 *
 * @author Benjamin Gil Flores
 * @version NaN
 * @package Stella\Modules\Terminal\Procedure
 */
interface StellaProcedureInterface
{
    public function setOptions (): self;
    public function setAction (string $action): self;
    public function do(): array;
}