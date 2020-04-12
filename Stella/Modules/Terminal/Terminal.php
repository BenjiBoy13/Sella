<?php


namespace Stella\Modules\Terminal;

/**
 * Class Terminal
 *
 * Handles the execution of a stella command
 * procedure.
 *
 * @author enjamin Gil Flores
 * @version 0.1
 * @package Stella\Modules\Terminal
 */
class Terminal
{
    /**
     * Creates an instance of the ProcedureHandler class and
     * pass to it an instance of the procedure to run
     *
     * @param string $procedureFromCommand
     */
    public static function execute (string $procedureFromCommand)
    {
        if ($procedureFromCommand !== "" && strpos($procedureFromCommand, ":")) {
            // Split action and procedure
            $procedure = explode(":", $procedureFromCommand)[0];
            $action = explode(":", $procedureFromCommand)[1];

            $procedure = "Stella\\Modules\\Terminal\\Procedure\\" .  ucfirst($procedure) . "Procedure";

            // Look if procedure exists
            if (class_exists($procedure)) {
                // Calling procedure handler
                if ($action !== "" && $action !== null) {
                    $handler = new ProcedureHandler(new $procedure, $action);
                    $handler->initiateProcedure()->logProcedureResponse();
                    return;
                }

                print "Error: action of procedure cannot be blank or null";
                exit;
            }

            print "Error: '$procedureFromCommand' is not a valid procedure";
            exit;
        }

        print "Error: invalid command";
        exit;
    }
}