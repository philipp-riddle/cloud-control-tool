<?php

namespace Phiil\CloudTools\Controller;

class HomeController extends Controller
{
    public function getRoutes(): array
    {
        return [
            'index' => 'home',
        ];
    }

    public function home()
    {
        return 'hi!';
    }

    public function authenticate(): bool
    {
        return true;
    }
}