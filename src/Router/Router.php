<?php declare(strict_types=1);

namespace Framework312\Router;

interface Router {
    // pour dire au router quelle chemin est associé à quelle classe ou vue
    public function register(string $path, string|object $class_or_view);

    // prend la requete et la renvoie
    public function serve(mixed ...$args);
}

?>
