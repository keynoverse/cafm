<?php

namespace App\Core;

class View
{
    private string $layout = 'main';
    private string $viewPath = __DIR__ . '/../../src/Views/';
    private array $data = [];

    public function setLayout(string $layout)
    {
        $this->layout = $layout;
    }

    public function render(string $view, array $params = [])
    {
        $this->data = $params;
        
        // Extract variables for the view
        extract($params);
        
        // Start output buffering
        ob_start();
        
        // Include the view file
        $viewFile = $this->viewPath . $view . '.php';
        if (!file_exists($viewFile)) {
            throw new \Exception("View file not found: $viewFile");
        }
        require $viewFile;
        
        // Get the view content
        $content = ob_get_clean();
        
        // Include the layout
        $layoutFile = $this->viewPath . 'layouts/' . $this->layout . '.php';
        if (!file_exists($layoutFile)) {
            throw new \Exception("Layout file not found: $layoutFile");
        }
        
        ob_start();
        require $layoutFile;
        return ob_get_clean();
    }

    public function partial(string $view, array $params = [])
    {
        extract(array_merge($this->data, $params));
        
        $viewFile = $this->viewPath . 'partials/' . $view . '.php';
        if (!file_exists($viewFile)) {
            throw new \Exception("Partial view file not found: $viewFile");
        }
        
        ob_start();
        require $viewFile;
        return ob_get_clean();
    }

    public function asset(string $path)
    {
        return '/assets/' . ltrim($path, '/');
    }

    public function url(string $path)
    {
        return '/' . ltrim($path, '/');
    }

    public function csrf_token()
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public function csrf_field()
    {
        return '<input type="hidden" name="csrf_token" value="' . $this->csrf_token() . '">';
    }

    public function old(string $key, $default = '')
    {
        return $_SESSION['old'][$key] ?? $default;
    }

    public function error(string $key)
    {
        return $_SESSION['errors'][$key] ?? null;
    }

    public function hasError(string $key)
    {
        return isset($_SESSION['errors'][$key]);
    }

    public function flash(string $key)
    {
        if (isset($_SESSION['flash'][$key])) {
            $message = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $message;
        }
        return null;
    }
} 