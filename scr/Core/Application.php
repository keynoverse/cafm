<?php

namespace App\Core;

class Application
{
    private static $instance = null;
    private $container = [];
    private $router;
    private $request;
    private $response;

    public function __construct()
    {
        self::$instance = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getRouter()
    {
        return $this->router;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function run()
    {
        try {
            $this->router->dispatch();
        } catch (\Exception $e) {
            $this->response->setStatusCode(500);
            echo $e->getMessage();
        }
    }

    public function set($key, $value)
    {
        $this->container[$key] = $value;
    }

    public function get($key)
    {
        return $this->container[$key] ?? null;
    }
} 