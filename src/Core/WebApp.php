<?php

namespace Phiil\CloudTools\Core;

use Phiil\CloudTools\Core\Controller\ControllerResolver;

/**
 * This child of App gets used for any Web actions and makes it easier to handle those requests.
 */
class WebApp extends App
{
    protected $resolver; // ControllerResolver

    public function __construct()
    {
        parent::__construct();

        $this->resolver = new ControllerResolver($this);
        $this->resolver->load();

        $this->twig = new TwigComponent();
    }

    public function getResolver(): ControllerResolver
    {
        return $this->resolver;
    }

    public function getTwig(): TwigComponent
    {
        return $this->twig;
    }
}