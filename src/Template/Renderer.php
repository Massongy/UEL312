<?php

namespace Framework312\Template;

interface Renderer
{
    
    public function render(string $template, array $data = []): string;

    
    public function register(string $tag): void;
}