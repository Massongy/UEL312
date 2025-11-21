<?php

namespace Framework312\Router\View;

use Framework312\Router\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class TemplateView
{
    // Méthode à implémenter dans les vues
    abstract public function get(Request $request): array;

    // Méthode de rendu minimale pour Twig
    public function render(Request $request, $engine): Response
    {
        $data = $this->get($request);
        $templateName = (new \ReflectionClass($this))->getShortName() . '/show.html.twig';
        $html = $engine->render($templateName, $data);
        return new Response($html);
    }
}
