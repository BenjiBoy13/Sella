<?php


namespace Stella\Modules\Twig\Extensions;


use Twig\Extension\AbstractExtension;
use Twig\Extension\ExtensionInterface;

/**
 * -----------------------------------------
 * Class StellaTwigFunctions
 * -----------------------------------------
 *
 * Defines Stella Twig functions extension
 *
 * @author Benjamin Gil Flores
 * @version NaN
 * @package Stella\Modules\Twig\Extensions
 */
class StellaTwigFunctions extends AbstractExtension implements ExtensionInterface
{
    /**
     * @return array
     */
    public function getFunctions() : array
    {
        return array();
    }
}