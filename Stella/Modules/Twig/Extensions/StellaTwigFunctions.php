<?php


namespace Stella\Modules\Twig\Extensions;


use Twig\Extension\AbstractExtension;
use Twig\Extension\ExtensionInterface;
use Twig\TwigFunction;

/**
 * Class StellaTwigFunctions
 *
 * Defines Stella Twig functions extension
 *
 * @author Benjamin Gil Flores
 * @version 0.1.2
 * @since 0.1
 * @package Stella\Modules\Twig\Extensions
 */
class StellaTwigFunctions extends AbstractExtension implements ExtensionInterface
{
    /**
     * @return array
     */
    public function getFunctions() : array
    {
        return array(
            new TwigFunction('asset', [$this, 'asset'])
        );
    }

    public function asset (string $asset)
    {
        return "/bundles/" . $asset;
    }
}