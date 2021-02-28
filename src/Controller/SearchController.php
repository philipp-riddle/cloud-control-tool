<?php

namespace Phiil\CloudTools\Controller;

use Phiil\CloudTools\Core\Controller\Controller;
use Phiil\CloudTools\Database\Entity\File;

class SearchController extends Controller
{
    public function search()
    {
        $search = $this->getRequestParam('search');
        $type = $this->getRequestParam('type');
        $files = $this->getFileRepository()->fetchAllByDirectoryPrefix($search, [$type]);

        return $this->render('search/index.html.twig', [
            'search' => $search,
            'files' => $files,
            'types' => $this->getFileRepository()->fetchAllByTypesDirectoryPrefix($search),
            'parentDirectory' => File::calculateDepth($search) > 0 ? dirname($search) : null,
        ]);
    }

    // METHODS TO REGISTER THE CONTROLLER PROPERLY

    public function getRoutes(): array
    {
        return [
            'search' => 'search',
        ];
    }

    public function authenticate(): bool
    {
        return true;
    }
}
