<?php

namespace Phiil\CloudTools\Core\Controller;

use Phiil\CloudTools\Controller\DirectoryController;
use Phiil\CloudTools\Controller\HomeController;
use Phiil\CloudTools\Controller\SearchController;
use Phiil\CloudTools\Core\WebApp;
use Phiil\CloudTools\Core\Exception\ControllerMethodMissingException;

class ControllerResolver
{
    private $app;
    private $controllers;

    public function __construct(WebApp $app)
    {
        $this->app = $app;
        $this->controllers = [];
    }

    public function load()
    {
        $this->registerController(new DirectoryController($this));
        $this->registerController(new HomeController($this));
        $this->registerController(new SearchController($this));
    }

    public function registerController(Controller $controller)
    {
        $this->controllers[] = $controller;
    }

    /**
     * Resolves a resolution to a controller action.
     *
     * @param string $controllerName the controller action name that should be searched for
     *
     * @return mixed\null returns the contents of the called method, NULL if the method is not accessible
     */
    public function resolve(string $controllerName)
    {
        $resolution = $this->resolveToControllerResolution($controllerName);

        if ($resolution) {
            return $resolution->execute(); // execute the controller's method
        }

        return null;
    }

    /**
     * Resolves a $controllerName to a specific controller + method.
     * This result gets encapsulated inside a ControllerResolution object.
     *
     * @param string $controllerName the controller name that should be looked for
     *
     * @return ControllerResolution\null ControllerResolution if the route could be found, null if not
     *
     * @throws ControllerMethodMissingException if a controller tries to get called which has not the route method implemented
     */
    public function resolveToControllerResolution(string $controllerName): ?ControllerResolution
    {
        foreach ($this->controllers as $controller) {
            foreach ($controller->getRoutes() as $routeName => $routeMethod) {
                if ($controllerName === $routeName) {
                    if (!\method_exists($controller, $routeMethod)) {
                        throw new ControllerMethodMissingException(\sprintf('Controller "%s": method is not accessible: "%s"', $controller, $routeMethod));
                    }

                    return new ControllerResolution($controller, $routeName, $routeMethod);
                }
            }
        }

        return null;
    }

    /**
     * Generates an URL for a route name.
     *
     * @param string $routeName the route that the URL should be generated for
     *
     * @param string|null returns the path if the route could be found, NULL otherwise
     */
    public function generateURL(string $routeName): ?string
    {
        $resolution = $this->resolveToControllerResolution($routeName);

        if ($resolution) {
            return $resolution->getPath(); // get the URL for this resolution
        }

        return null;
    }

    public function getApp(): WebApp
    {
        return $this->app;
    }
}
