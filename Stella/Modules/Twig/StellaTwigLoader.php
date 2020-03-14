<?php


namespace Stella\Modules\Twig;


use Stella\Modules\Twig\Extensions\StellaTwigGlobals;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class StellaTwigLoader
{
    private Environment $twigEnvironment;
    private FilesystemLoader $twigLoader;

    public function __construct()
    {
        $this->twigLoader = new FilesystemLoader("templates/");
        $this->twigEnvironment = new Environment($this->twigLoader);
    }

    public function getTwigEnvironment () : Environment
    {
        $this->twigEnvironment->addExtension(new StellaTwigGlobals());
        return $this->twigEnvironment;
    }
}