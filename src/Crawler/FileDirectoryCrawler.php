<?php 

namespace Phiil\CloudTools\Crawler;

use DateTime;
use Error;
use Exception;
use Phiil\CloudTools\Crawler\FileCrawler;
use Phiil\CloudTools\Database\Entity\Directory;
use Phiil\CloudTools\Database\Entity\File;
use Phiil\CloudTools\Database\Entity\SimpleFile;
use Phiil\CloudTools\Database\Repository\FileRepository;

class FileDirectoryCrawler
{
    const MAX_DEPTH = 4;

    private $fileCrawler;
    private $repository;

    private $indexedFilesCount = 0;

    public function __construct(FileCrawler $fileCrawler)
    {
        $this->fileCrawler = $fileCrawler;
        $this->repository = new FileRepository($this->fileCrawler->getApp()->getMongoService()); // connection to the database
        $this->indexedFilesCount = 0;
    }

    public function crawlDirectory(string $directory): array
    {
        $container = [];
        $this->_crawlDirectory($directory, $container);

        return $container;
    }

    /**
     * Crawls a directory.
     * 
     * @param string $directory the directory that should be crawler
     * @param string $container the container and array which gets filled with file names
     * 
     * @param array returns a container with all dirs and files found in the current subdirectory
     */
    private function _crawlDirectory(string $directory, array &$container, int $depth = 1): array
    {
        if (!\is_writable($directory)) {
            return [];
        }

        try {
            $scanDir = @scandir($directory); // suppress warnings

            if (false === $scanDir) {
                return [];
            }

            $scanned = \array_diff($scanDir, ['..', '.']); // to get rid of the dots
        } catch (Error | Exception $err) {
            return []; // e.g. no access to the directory
        }

        foreach ($scanned as $file) {
            $filePath = $directory.$file;
            $entityFile = $this->repository->fetchByPath($filePath);

            if (null !== $entityFile) {
                $entityFile->incrementIndexedCounter();
            } else {
                if (\is_dir($filePath)) {
                    $entityFile = new Directory();
                    $entityFile->setSize(0);
                } else if (\is_file($filePath)) {
                    $entityFile = new SimpleFile();
                    $entityFile->setFileName(\basename($file));
                    $entityFile->setType(\pathinfo($file, PATHINFO_EXTENSION));
                    $entityFile->setSize(\filesize($filePath));
                } else {
                    continue; // something else
                }

                $entityFile->setPath($filePath);
                $entityFile->setDirectory(\dirname($file));
                $entityFile->setFirstIndexedAt(new DateTime());
                $entityFile->setDepth(File::calculateDepth($directory));
            }

            $entityFile->setLastIndexedAt(new DateTime());
            $this->repository->flush($entityFile);
            $this->_logIndexedFile($entityFile);

            if ($entityFile->isDirectory()) {
                $container['dirs'] = $entityFile;

                if (self::MAX_DEPTH > $depth) {
                    $this->_crawlDirectory($filePath, $container, $depth + 1);
                }
            } else {
                $container['files'] = $entityFile;
            }
        }

        return $container;
    }

    protected function _logIndexedFile(File $file)
    {
        $this->indexedFilesCount++;

        if (0 === $this->indexedFilesCount % 500) {
            printf('> Indexed %s files.'.PHP_EOL, $this->indexedFilesCount);
        }
    }
}