<?php

namespace App\Core;

abstract class Controller
{
    protected Request $request;
    protected Response $response;
    protected View $view;

    public function __construct()
    {
        $this->request = Application::getInstance()->getRequest();
        $this->response = Application::getInstance()->getResponse();
        $this->view = new View();
    }

    protected function render($view, $params = [])
    {
        return $this->view->render($view, $params);
    }

    protected function json($data, $statusCode = 200)
    {
        $this->response->setStatusCode($statusCode);
        return $this->response->json($data);
    }

    protected function redirect($url)
    {
        return $this->response->redirect($url);
    }

    protected function validate($data, $rules)
    {
        $validator = new Validator($data, $rules);
        return $validator->validate();
    }

    protected function isAuthenticated()
    {
        return isset($_SESSION['user_id']);
    }

    protected function requireAuth()
    {
        if (!$this->isAuthenticated()) {
            return $this->redirect('/login');
        }
    }

    protected function requireRole($role)
    {
        $this->requireAuth();
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== $role) {
            return $this->redirect('/unauthorized');
        }
    }

    protected function getCurrentUser()
    {
        if (!$this->isAuthenticated()) {
            return null;
        }

        $userModel = new \App\Models\User();
        return $userModel->find($_SESSION['user_id']);
    }

    protected function flash($key, $message)
    {
        $_SESSION['flash'][$key] = $message;
    }

    protected function getFlash($key)
    {
        if (isset($_SESSION['flash'][$key])) {
            $message = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $message;
        }
        return null;
    }
} 