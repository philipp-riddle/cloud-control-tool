<?php

namespace Phiil\CloudTools\Core\Controller;

use Phiil\CloudTools\Database\Repository\FileRepository;
use Phiil\CloudTools\Core\Exception\ControllerTemplateNotFoundException;

abstract class Controller
{
    private $resolver;
    
    public function __construct(ControllerResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    abstract public function getRoutes(): array;
    abstract public function authenticate(): bool;

    public function render(string $templateName, array $data): string
    {
        $twig = $this->resolver->getApp()->getTwig();

        if (!$twig->getTwigLoader()->exists($templateName)) {
            throw new ControllerTemplateNotFoundException('Could not find template: '.$templateName);
        }

        $data = \array_merge($data, [
            'controller' => $this, // inject the controller instance in the template
        ]);

        return $twig->getTwigEnvironment()->render($templateName, $data);
    }

    public function getRequestParam(string $paramName, $default = null)
    {
        if (!isset($_GET[$paramName])) {
            return $default;
        }

        return \trim($_GET[$paramName]);
    }

    public function getFileRepository(): FileRepository
    {
        return new FileRepository($this->resolver->getApp()->getMongoService());
    }

    public function getResolver(): ControllerResolver
    {
        return $this->resolver;
    }
}
