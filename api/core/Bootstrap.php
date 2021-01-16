<?php

namespace Api\Core;

class Bootstrap 
{
    public function __construct() 
    {
        if (Routes::$useDefaults)
        {
            self::setup_default_routes();
        }
        else
        {
            self::setup_defined_routes();
        }
    }

    private function setup_default_routes()
    {
        $url = isset($_GET['url']) ? $_GET['url'] : null;
        $url = rtrim($url, '/');
        $url = explode('/', $url);

        $controller = empty($url[0]) ? 'home'  : $url[0];
        $action     = empty($url[1]) ? 'index' : $url[1];

        switch (strtolower($_SERVER['REQUEST_METHOD'])) 
        {
            case 'get':
                $parameters = empty($url[2]) ? array() : array_slice($url, 2);

                break;
            case 'post':
                $parameters = json_decode(file_get_contents("php://input"), true);

                break;
            default:
                $parameters = null;
        }

        $this->callControllerAction($controller, $action, $parameters);
    }

    private function setup_defined_routes()
    {
        switch (strtolower($_SERVER['REQUEST_METHOD'])) 
        {
            case 'get':
                // This part is left out. Beyond this task
                return false;

                break;
            case 'post':
                $url        = isset(($_GET['url'])) ? '/' . rtrim($_GET['url'], '/') : '/';
                $function   = isset(Routes::$posts[$url]) ? Routes::$posts[$url] : null;
                $parameters = json_decode(file_get_contents("php://input"), true);

                break;
            default:
                return false;
        }

        if (is_null($function))
        {
            return false;
        }

        $function   = explode('@', $function);
        $controller = $function[0];
        $action     = $function[1];

        $this->callControllerAction(strtolower($controller), $action, $parameters);     
    }

    private function callControllerAction($controller, $action, $parameters)
    {
        if (!file_exists('api/controllers/' . $controller . '.php')) 
        {
            return false;           
        }

        $controllerClassName = '\\Api\\Controllers\\' . ucfirst($controller);
        $controllerClass     = new $controllerClassName;

        if (!method_exists($controllerClass, $action)) 
        {
            return false;
        }

        if (method_exists($controllerClass, 'checkBasicAuth')) 
        {
            $controllerClass->checkBasicAuth();
        }

        $controllerClass->{$action}($parameters);
    }
}
