<?php 

namespace Phiil\CloudTools\Crawler;

use DateTime;
use Phiil\CloudTools\Crawler\FileCrawler;
use Phiil\CloudTools\Database\Entity\Directory;
use Phiil\CloudTools\Database\Entity\SimpleFile;
use Phiil\CloudTools\Database\Repository\FileRepository;

class FileDirectoryCrawler
{
    const MAX_DEPTH = 4;

    private $fileCrawler;
    private $repository;

    public function __construct(FileCrawler $fileCrawler)
    {
        $this->fileCrawler = $fileCrawler;
        $this->repository = new FileRepository($this->fileCrawler->getApp()->getMongoService()); // connection to the database
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
        $scanned = \array_diff(\scandir($directory), ['..', '.']); // to get rid of the dots

        foreach ($scanned as $file) {
            $filePath = $directory.'/'.$file;
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
            }

            $entityFile->setLastIndexedAt(new DateTime());
            $this->repository->flush($entityFile);

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
}