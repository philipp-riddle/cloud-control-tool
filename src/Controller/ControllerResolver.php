<?php

namespace Phiil\CloudTools\Controller;

use Phiil\CloudTools\Core\CloudApp;
use Phiil\CloudTools\Exception\ControllerMethodMissingException;

class ControllerResolver
{
    private $app;
    private $controllers;

    public function __construct(CloudApp $app)
    {
        $this->app = $app;
        $this->controllers = [];
    }

    public function load()
    {
        $this->registerController(new DirectoryController($this));
        $this->registerController(new HomeController($this));
    }

    public function registerController(Controller $controller)
    {
        $this->controllers[] = $controller;
    }

    public function resolve(string $controllerName)
    {
        foreach ($this->controllers as $controller) {
            foreach ($controller->getRoutes() as $routeName => $routeMethod) {
                if ($controllerName === $routeName) {
                    if (!\method_exists($controller, $routeMethod)) {
                        throw new ControllerMethodMissingException(\sprintf('Controller "%s": method is not accessible: "%s"', $controller, $routeMethod));
                    }

                    return $controller->$routeMethod();
                }
            }
        }

        return null;
    }

    public function getApp(): CloudApp
    {
        return $this->app;
    }
}