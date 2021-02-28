<?php

namespace Phiil\CloudTools\Command;

use Phiil\CloudTools\Core\Command\CommandArgumentResolution;
use Phiil\CloudTools\Core\Command\CommandResolver;
use Phiil\CloudTools\Crawler\FileCrawler;

class CrawlDirectoryCommand extends CloudCommand
{
    public function __construct(CommandResolver $resolver)
    {
        parent::__construct('crawl', $resolver);
    }

    protected function _execute(CommandArgumentResolution $resolution): bool
    {
        if ($resolution->getArguments()->isEmpty()) {
            $this->style->writeLine('No arguments provided - please provide the directories you want to crawl by passing them as arguments.');

            return false;
        }

        $crawler = new FileCrawler($this->resolver->getApp());

        foreach ($resolution->getArguments()->getAll() as $directory) {
            $this->style->writeLine('Crawling: '.$directory);
            $crawler->crawl($directory);
        }

        return true; // needs to be implemented
    }
}
