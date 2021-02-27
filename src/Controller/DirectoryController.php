<?php

namespace Phiil\CloudTools\Controller;

use Phiil\CloudTools\Core\Controller\Controller;

class DirectoryController extends Controller
{
    public function directoryDetails()
    {
        $dir = $this->getRequestParam('dir');

        $files = $this->getFileRepository()->fetchAllByDirectory($dir);

        return $this->render('directory/details.php', [
            'allFiles' => $files,
            'dirFiles' => $this->getFileRepository()->getFilesInDirectory($dir),
        ]);
    }

    // METHODS TO REGISTER THE CONTROLLER

    public function getRoutes(): array
    {
        return [
            'directory-details' => 'directoryDetails',
        ];
    }

    public function authenticate(): bool
    {
        return true;
    }
}