<?php

namespace Framework312\Template;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigRenderer implements Renderer
{
    private Environment $twig;
    private FilesystemLoader $loader;


    public function __construct(string $templatesPath)
    {
        $this->loader = new FilesystemLoader($templatesPath);
        $this->twig = new Environment($this->loader);
    }

   
    public function render(string $template, array $data = []): string
    {
        return $this->twig->render($template, $data);
    }

    
    public function register(string $tag): void
    {
        $this->loader->addPath($this->loader->getPaths()[0] . '/' . $tag, $tag);
    }
}
