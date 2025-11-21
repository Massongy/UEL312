<?php

namespace Framework312\Template;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Renderer
{
    private Environment $twig;
    private FilesystemLoader $loader;

    public function __construct()
    {
        // Charge le dossier templates/ principal
        $this->loader = new FilesystemLoader(__DIR__ . '/../../../templates');

        // Instancie Twig avec ce loader
        $this->twig = new Environment($this->loader, [
            'cache' => false, // mettre un dossier si tu veux activer le cache
        ]);
    }

    /**
     * Compile un template en string.
     * 
     * @param string $templateName  Le nom du template (ex: "Home/index.html.twig")
     * @param array  $data          Les variables à injecter dans le template
     * @return string               Le HTML compilé
     */
    public function render(string $templateName, array $data = []): string
    {
        return $this->twig->render($templateName, $data);
    }

    /**
     * Enregistre un tag, c’est-à-dire un sous-dossier dans /templates.
     * 
     * @param string $tag Exemple : "HelloWorld".
     */
    public function register(string $tag): void
    {
        // Ce tag correspond à un sous-dossier de templates/
        $path = __DIR__ . '/../../../templates/' . $tag;

        if (!is_dir($path)) {
            throw new \RuntimeException("Renderer: impossible de trouver le dossier '$path'");
        }

        // Ajoute ce sous-dossier au loader Twig
        $this->loader->addPath($path, $tag);
    }
}
