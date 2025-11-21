<?php

require __DIR__ . '/../vendor/autoload.php'; 

use Framework312\Router\View\BaseView;
use Framework312\Router\{Request, SimpleRouter};
use Framework312\Template\Renderer;
use Framework312\Template\TwigRenderer;

use Framework312\Router\View\TemplateView;

// création de al classe Book, qui hérite des caractéristique de TemplateView

class Book extends TemplateView
{
    // Méthode GET
    function get(Request $request): array {
        // Récupère l'ID passé dans l'URL
        $id = $request->attributes->get('id');

        // Retourne les données à injecter dans le template
        return [
            'id' => $id,
            'title' => 'Livre test #' . $id
        ];
    }

    // Méthode POST (optionnelle)
    function post(Request $request): array {
        return [];
    }
}
$engine = new TwigRenderer(__DIR__ . '/templates/');
$router = new SimpleRouter($engine);
$router->register('/book/:id', Book::class); //register est une fonction appartenant à la classe SimpleRouter : son but est d'associer une classe à une route (url)
$router->serve();
?>