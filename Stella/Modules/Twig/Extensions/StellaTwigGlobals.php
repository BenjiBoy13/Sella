<?php


namespace Stella\Modules\Twig\Extensions;


use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class StellaTwigGlobals extends AbstractExtension implements GlobalsInterface
{
    public function getGlobals() : array
    {
        return array(
          'global1' => 'Variable global 1'
        );
    }
}