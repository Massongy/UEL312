<?php declare(strict_types=1);

namespace Framework312\Router;

use Framework312\Router\Exception as RouterException;
use Framework312\Template\Renderer;
use Symfony\Component\HttpFoundation\Response;

class SimpleRouter implements Router
{
    private Renderer $engine;
    private array $routes = [];

    public function __construct(Renderer $engine)
    {
        $this->engine = $engine;
    }

    /**
     * Enregistre une route avec une classe de vue
     */
    public function register(string $path, string|object $classOrObject)
    {
        $this->routes[$path] = $classOrObject;
    }

    /**
     * Sert la requête HTTP actuelle
     */
    public function serve(mixed ...$args): void
    {
        $httpRequest = Request::createFromGlobals();
        $requestedPath = $httpRequest->getPathInfo();

        foreach ($this->routes as $routePattern => $viewClassOrObject) {

            // Transforme les paramètres :param en regex nommée
            $regexPattern = preg_replace('#:(\w+)#', '(?P<$1>[^/]+)', $routePattern);

            if (preg_match("#^$regexPattern$#", $requestedPath, $matches)) {

                // Injecte les paramètres capturés dans la requête
                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $httpRequest->attributes->set($key, $value);
                    }
                }

                // Instancie la vue en lui passant le Renderer
                $viewInstance = is_string($viewClassOrObject)
                    ? new $viewClassOrObject($this->engine)
                    : $viewClassOrObject;

                // Appelle render() sans repasser le renderer
                $response = $viewInstance->render($httpRequest);

                $response->send();
                return;
            }
        }

        // Aucun pattern ne correspond : 404
        $response = new Response('404 Not Found', 404);
        $response->send();
    }
}
