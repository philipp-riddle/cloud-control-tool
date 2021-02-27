<?php

namespace Phiil\CloudTools\Controller;

use Phiil\CloudTools\Database\Repository\FileRepository;

abstract class Controller
{
    private $resolver;
    
    public function __construct(ControllerResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    public abstract function getRoutes(): array;
    public abstract function authenticate(): bool;

    public function getRequestParam(string $paramName, string $arrayName = '_GET')
    {
        return \trim($_GET[$paramName] ?? '');
    }

    public function getFileRepository(): FileRepository
    {
        return new FileRepository($this->resolver->getApp()->getMongoService());
    }

    public function render(string $templateName, array $data): string
    {
        ob_start();
        require dirname(__FILE__).'/../../templates/'.$templateName;
        
        return ob_get_clean();
    }
}