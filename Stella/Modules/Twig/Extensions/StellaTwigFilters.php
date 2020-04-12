<?php


namespace Stella\Modules\Twig\Extensions;


use Twig\Extension\AbstractExtension;
use Twig\Extension\ExtensionInterface;

/**
 * Class StellaTwigFilters
 *
 * Defines Stella Twig filters extension
 *
 * @author Benjamin Gil Flores
 * @version 0.1
 * @package Stella\Modules\Twig\Extensions
 */
class StellaTwigFilters extends AbstractExtension implements ExtensionInterface
{
    /**
     * @return array
     */
    public function getFilters() : array
    {
        return array();
    }
}