<?php

namespace Phiil\CloudTools\Database\Repository;

use Phiil\CloudTools\Database\Entity\File;
use Phiil\CloudTools\Database\MongoService;

class FileRepository extends Repository
{
    public function __construct(MongoService $mongoService)
    {
        parent::__construct(File::class, $mongoService);
    }

    public function fetchByPath(string $filePath): ?File
    {
       $result = $this->mongoService->getFilesCollection()->findOne(['path' => $filePath]);

       return $this->__fact($result);
    }

    public function fetchAllByDirectory(string $directory)
    {
        $result = $this->mongoService->getFilesCollection()->aggregate([
            ['$match' =>
                ['path' => ['$regex' => $directory]],
            ],
        ]);

        return $this->__factArray($result);
    }

    /**
     * Gets all the files in the current directory (only one layer)
     */
    public function getFilesInDirectory(string $directory)
    {
        $result = $this->mongoService->getFilesCollection()->findOne([
            'directory' => $directory,
            // 'depth' => File::calculateDepth($directory),
        ]);

        return $this->__factArray($result);
    }

    public function getTotalIndexedFiles(): int
    {
        $result = $this->mongoService->getFilesCollection()->countDocuments([]);

        return $result ?? 0;
    }

    public function getTotalSize(): int
    {
        $result = $this->mongoService->getFilesCollection()->aggregate([
            ['$group' => 
                [
                    '_id' => null,
                    'size' => ['$sum' => '$size'],
                ],
            ],
        ]);

        if (!$result) {
            return 0;
        }

        return $this->mongoService->toArray($result)[0]['size'];
    }

    public function getTotalDirectories(): int
    {
        return $this->mongoService->getFilesCollection()->countDocuments(['type' => 'directory']) ?? 0;
    }

    public function getTotalFiles(): int
    {
        return $this->mongoService->getFilesCollection()->countDocuments(['type' => ['$ne' => 'directory']]) ?? 0;
    }
}