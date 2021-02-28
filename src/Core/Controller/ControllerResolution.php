<?php

namespace Phiil\CloudTools\Core\Controller;

/**
 * This class represents the crucial connections to connect a route to a controller method.
 */
class ControllerResolution
{
    protected $resolution;
    protected $controllerMethod;

    public function __construct(Controller $controller, string $routeName, string $controllerMethod)
    {
        $this->controller = $controller;
        $this->routeName = $routeName;
        $this->controllerMethod = $controllerMethod;
    }

    /**
     * Calls the controller with the saved method.
     *
     * @return mixed whatever the controller returns
     */
    public function execute()
    {
        $method = $this->controllerMethod;

        return $this->getController()->$method();
    }

    public function getController(): Controller
    {
        return $this->controller;
    }

    public function getControllerMethod(): string
    {
        return $this->controllerMethod;
    }

    public function getPath()
    {
        return 'index.php?controller='.$this->routeName;
    }
}
