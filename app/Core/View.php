<?php
declare(strict_types=1);

namespace App\Core;

class View
{
    public static function render(string $viewPath, array $data = [], string $layout = 'main'): void
    {
        extract($data);
        
        $viewFile = BASE_PATH . '/app/Views/' . str_replace('.', '/', $viewPath) . '.php';
        $layoutFile = BASE_PATH . '/app/Views/layouts/' . $layout . '.php';

        if (!file_exists($viewFile)) {
            Response::html("<h1>Error: Vista [{$viewPath}] no encontrada en app/Views.</h1>", 500);
            return;
        }

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        if ($layout !== '' && file_exists($layoutFile)) {
            ob_start();
            require $layoutFile;
            $html = ob_get_clean();
            Response::html($html);
        } else {
            Response::html($content ?: '');
        }
    }
}