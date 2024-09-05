<?php

namespace App\Core;

class App
{
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();

        // Controller
        if (isset($url[0])) {
            $potentialController = ucfirst($url[0]) . 'Controller';

            if (file_exists(BASE_PATH . 'app/Controllers/' . $potentialController . '.php')) {
                $this->controller = $potentialController;
                unset($url[0]);
            }
        }

        $this->controller = "App\\Controllers\\" . $this->controller;
        $this->controller = new $this->controller();

        // Method
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // Params
        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl()
    {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}
