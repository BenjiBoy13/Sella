<?php


namespace Stella\Modules\Twig\Extensions;


use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

/**
 * Class StellaTwigGlobals
 *
 * Defines Stella Twig global extension
 *
 * @author Benjamin Gil Flores
 * @version 0.1
 * @package Stella\Modules\Twig\Extensions
 */
class StellaTwigGlobals extends AbstractExtension implements GlobalsInterface
{
    /**
     * @return array
     */
    public function getGlobals() : array
    {
        return array(
          'global1' => 'Variable global 1'
        );
    }
}