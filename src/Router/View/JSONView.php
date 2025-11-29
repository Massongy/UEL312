<?php

// JSONView.php

namespace Framework312\Router\View;

use Framework312\Router\Request;
use Symfony\Component\HttpFoundation\Response;

class JSONView extends BaseView
{
    public static function use_template(): bool
    {
        return false;
    }

    public function render(Request $request): Response
    {
        $method = strtolower($request->getMethod());
        $data = $this->$method($request);
        
        return new Response(json_encode($data), 200, ['Content-Type' => 'application/json']);
    }
}

?>