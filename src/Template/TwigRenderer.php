<?php
namespace Framework312\Template;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigRenderer extends Renderer
{
    private Environment $twig;
    private FilesystemLoader $loader;

    public function __construct(string $templatePath)
    {
        $this->loader = new FilesystemLoader($templatePath);
        $this->twig = new Environment($this->loader, [
            'cache' => false,
        ]);
    }

    public function render(string $templateName, array $data = []): string
    {
        return $this->twig->render($templateName, $data);
    }

    public function register(string $tag): void
    {
        $path = $this->loader->getPaths()[0] . '/' . $tag;
        if (!is_dir($path)) {
            throw new \RuntimeException("Renderer: impossible de trouver le dossier '$path'");
        }
        $this->loader->addPath($path, $tag);
    }
}
