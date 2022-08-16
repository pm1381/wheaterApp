<?php
namespace App\Core;

use App\Controller\Site\HomeController;
use App\Model\DisplayFormat;
use ReflectionMethod;

class Router
{
    private $routers;

    public function __construct()
    {
        $this->routers = [];
    }

    public function route($method, $action, $callback)
    {
        $action = trim($action, '/');
        $this->routers[$action] = ['method' => $method, 'callback' => $callback];
    }

    public function curlyBraceRoute($method, $action, $callback)
    {
        $action = trim($action, '/');
        $action = preg_replace('/{[^}]+}/', '(.*)', $action);
        $this->routers[$action] = ['method' => $method, 'callback' => $callback];
    }

    public function getRouters()
    {
        return $this->routers;
    }


    private function closureAction($matches, $handler, &$callback, &$params){
        // &$ make the argument pass by refrence;
        if (gettype($handler['callback']) == 'object') {
            $callback = $handler['callback'];
            unset($matches[0]);
            $params = $matches;
            call_user_func($callback, ...$params);
            return true;
        }
        return false;
    }

    private function annotationAction($matches, $handler, &$callback, &$params, $previousresult)
    {
        if (! $previousresult) {
            if (preg_match("%^\w+@\w+@\w+$%", $handler['callback'], $classMethod)) {
                unset($matches[0]);
                $params = $matches;
                list($where, $controller, $methodName) = explode('@', $handler['callback']);
                try {   
                    if ($where == 'site') {
                        $baseController = CONTROLLER_NAMESPACE . '\\Site\\';
                    } else {
                        // admin
                        $baseController = CONTROLLER_NAMESPACE . '\\Admin\\';
                    }
                    $controller = $baseController . $controller . 'Controller';
                    $reflectedMethod = new ReflectionMethod($controller, $methodName);
                    if ($reflectedMethod->isPublic() && !($reflectedMethod->isAbstract()))
                    {
                        if ($reflectedMethod->isStatic()) {
                            forward_static_call_array(array($controller, $methodName), $params);
                        } else {
                            // make sure it can be instanciated
                            if (\is_string($controller)) {
                                $controller = new $controller();
                            }
                            $controllerObject = new $controller();
                            $callback = array($controllerObject, $methodName);
                            call_user_func_array($callback, $params);
                        }
                        return true;
                    }
                } catch (\ReflectionException $reflect) {
                    DisplayFormat::jsonFormat(false, $reflect->getMessage());
                    return false;
                }
                return false;
            }
        }
        return false;
    }

    public function dispatch($action)
    {
        $closureResult = false; $annotationResult = false;
        $action = trim($action, '/');
        $sentMethod = $_SERVER['REQUEST_METHOD'];
        $callback = null; $params = [];
        foreach ($this->routers as $router => $handler) {
            if (strtoupper($handler['method']) == $sentMethod && preg_match("%^{$router}$%", $action, $matches)) {
                $closureResult = $this->closureAction($matches, $handler, $callback, $params);
                $annotationResult = $this->annotationAction($matches, $handler, $callback, $params, $closureResult);
            }

            if ($closureResult || $annotationResult) {
                break;
            }
        }
        if ($callback == null) {
            http_response_code(404);
            DisplayFormat::jsonFormat(false, 'page not found');
        }
    }
}