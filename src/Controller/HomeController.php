<?php

namespace Phiil\CloudTools\Controller;

use Phiil\CloudTools\Core\Controller\Controller;

class HomeController extends Controller
{
    public function getRoutes(): array
    {
        return [
            'index' => 'home',
        ];
    }

    // METHODS TO REGISTER THE CONTROLLER

    public function home()
    {
        return $this->render('home/index.html.twig', [
            'totalIndexed' => $this->getFileRepository()->getTotalIndexedFiles(),
            'totalSize' => $this->getFileRepository()->getTotalSize(),
            'totalDirectories' => $this->getFileRepository()->getTotalDirectories(),
            'totalFiles' => $this->getFileRepository()->getTotalFiles(),
        ]);
    }

    public function authenticate(): bool
    {
        return true;
    }
}