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

    public function fetchAllByDirectoryPrefix(string $directory, ?string $type = null): array
    {
        $matchQuery = ['path' => ['$regex' => $directory]];

        if (null !== $type) {
            $matchQuery['type'] = $type;
        }

        $result = $this->mongoService->getFilesCollection()->aggregate([
            ['$match' => $matchQuery],
            ['$limit' => 100],
        ]);

        return $this->__factArray($result);
    }

    public function fetchAllByTypesDirectoryPrefix(string $directory): array
    {
        $result = $this->mongoService->getFilesCollection()->aggregate([
            ['$group' =>
                [
                    '_id' => '$type',
                    'count' => ['$sum' => 1],
                ],
            ],
            ['$sort' => 
                [
                    'count' => -1,
                ],
            ],
            ['$project' =>
                [
                    'type' => '$_id',
                    'count' => '$count',
                ],
            ],
            ['$limit' => 10],
        ]);

        if (!$result) {
            return 0;
        }

        return $this->mongoService->toArray($result); // only return the types
    }

    /**
     * Gets all the files in the current directory (only one layer)
     */
    public function getFilesInDirectory(string $directory): array
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