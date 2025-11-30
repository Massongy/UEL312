<?php

// HTMLView.php

namespace Framework312\Router\View;

use Framework312\Router\Request;
use Symfony\Component\HttpFoundation\Response;

class HTMLView extends BaseView
{
    public static function use_template(): bool
    {
        return false;
    }

    public function render(Request $request): Response
    {
        $method = strtolower($request->getMethod());
        $data = $this->$method($request);
        
        return new Response($data, 200, ['Content-Type' => 'text/html']);
    }
}

?>