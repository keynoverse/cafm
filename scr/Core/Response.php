<?php

namespace App\Core;

class Response
{
    public function setStatusCode(int $code)
    {
        http_response_code($code);
    }

    public function redirect($url)
    {
        header("Location: $url");
        exit;
    }

    public function json($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function setHeader($name, $value)
    {
        header("$name: $value");
    }

    public function setCookie($name, $value, $expires = 0, $path = '/', $domain = '', $secure = false, $httponly = true)
    {
        setcookie($name, $value, $expires, $path, $domain, $secure, $httponly);
    }

    public function download($file, $filename = null)
    {
        if (!file_exists($file)) {
            $this->setStatusCode(404);
            return;
        }

        $filename = $filename ?? basename($file);
        $this->setHeader('Content-Type', 'application/octet-stream');
        $this->setHeader('Content-Disposition', "attachment; filename=\"$filename\"");
        $this->setHeader('Content-Length', filesize($file));
        readfile($file);
        exit;
    }
} 