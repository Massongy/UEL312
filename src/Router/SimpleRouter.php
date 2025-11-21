<?php declare(strict_types=1);

namespace Framework312\Router;

use Framework312\Router\Exception as RouterException;
use Framework312\Template\Renderer;
use Symfony\Component\HttpFoundation\Response;

class Route {
    private const VIEW_CLASS = 'Framework312\Router\View\BaseView';
    private const VIEW_USE_TEMPLATE_FUNC = 'use_template';
    private const VIEW_RENDER_FUNC = 'render';

    private string $view;

    public function __construct(string|object $class_or_view) {
        $reflect = new \ReflectionClass($class_or_view);
        $view = $reflect->getName();
        if (!$reflect->isSubclassOf(self::VIEW_CLASS)) {
            throw new RouterException\InvalidViewImplementation($view);
        }
        $this->view = $view;
    }

    public function call(Request $request, ?Renderer $engine): Response {
	    // TODO
    }
}

class SimpleRouter implements Router {
    private Renderer $engine;
    private array $routes=[];

    public function __construct(Renderer $engine) {
        $this->engine = $engine;
        // TODO
    }
    // pour parametrer les routes en associant chemin et classe
    public function register(string $path, string|object $class_or_view) {

        $this->routes[$path] = $class_or_view;

	    
    }

    // pour servir la requete à partir de l'url du client
    public function serve(mixed ...$args): void {
    // 1Crée une Request à partir des variables globales
    $httpRequest = Request::createFromGlobals();

    // Récupère le chemin demandé par l'utilisateur
    $requestedPath = $httpRequest->getPathInfo();

    // Parcourt les routes enregistrées pour trouver la View correspondante
    foreach ($this->routes as $routePattern => $viewClassOrObject) {

        // Transforme les paramètres :param en regex nommée
        $regexPattern = preg_replace('#:(\w+)#', '(?P<$1>[^/]+)', $routePattern);

        if (preg_match("#^$regexPattern$#", $requestedPath, $matches)) {

            // Injecte les paramètres capturés dans les attributs de la requête
            foreach ($matches as $key => $value) {
                if (is_string($key)) {
                    $httpRequest->attributes->set($key, $value);
                }
            }

            // Récupère la View associée à cette route
            $viewInstance = is_string($viewClassOrObject)
                ? new $viewClassOrObject()
                : $viewClassOrObject;

            // 3Appelle la méthode render de la View, qui retourne une Response
            $response = $viewInstance->render($httpRequest, $this->engine);

            // Envoie la Response au client
            $response->send();
            return;
        }
    }

    // Aucun pattern ne correspond : retourne un 404
    $response = new Response('404 Not Found', 404);
    $response->send();
}


    
}

?>
