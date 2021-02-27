<?php

namespace Phiil\CloudTools\Controller;

class DirectoryController extends Controller
{
    public function directoryDetails()
    {
        $dir = $this->getRequestParam('dir');

        $files = $this->getFileRepository()->fetchAllByDirectory($dir);

        return $this->render('directory/details.php', [
            'files' => $files,
        ]);
    }

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