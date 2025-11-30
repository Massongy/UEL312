<?php

// TemplateView.php

namespace Framework312\Router\View;

use Framework312\Router\Request;
use Framework312\Template\Renderer;
use Symfony\Component\HttpFoundation\Response;

class TemplateView extends BaseView
{
    protected Renderer $renderer;

    public function __construct(Renderer $renderer)
    {
        $this->renderer = $renderer;
        $this->renderer->register(static::class);
    }

    public static function use_template(): bool
    {
        return true;
    }

    public function render(Request $request, ?Renderer $renderer = null): Response
    {
        if ($renderer !== null) {
            $this->renderer = $renderer;
            $this->renderer->register(static::class);
        }
        
        $method = strtolower($request->getMethod());
        $data = $this->$method($request);
        
        $templateName = (new \ReflectionClass($this))->getShortName() . '/show.html.twig';
        
        $content = $this->renderer->render($templateName, $data);
        
        return new Response($content);
    }
}

?>