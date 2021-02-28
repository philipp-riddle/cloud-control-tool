<?php

namespace Phiil\CloudTools\Command;

use Phiil\CloudTools\Core\Command\CommandArgumentResolution;
use Phiil\CloudTools\Core\Command\CommandResolver;
use Phiil\CloudTools\Database\Repository\FileRepository;

class DeleteTypeCommand extends CloudCommand
{
    public function __construct(CommandResolver $resolver)
    {
        parent::__construct('delete:type', $resolver);
    }

    protected function _execute(CommandArgumentResolution $resolution): bool
    {
        if ($resolution->getArguments()->isEmpty()) {
            $this->style->writeLine('No arguments provided - please provide the types you want to delete.');

            return false;
        }

        $files = $this->_getFileRepository()->fetchAllByDirectoryPrefix('/', $resolution->getArguments()->getAll(), 10000);
        $this->meta->set('types', $resolution->getArguments()->getAll());
        $this->meta->set('foundfiles', \count($files));

        $this->style->writeLine(\sprintf('Given types: %s.', \implode(', ', $resolution->getArguments()->getAll())));
        $this->style->writeLine(\sprintf('Given flags: %s.', \implode(', ', $resolution->getFlags()->getAll())));
        $this->style->writeLine(\sprintf('Found %s files matching your given types.', \count($files)));
        $this->style->writeEmptyLine(2);

        if (!$resolution->getFlags()->hasValue('force')) {
            $this->style->writeLine('No --force flag set. Execute this command with the --force flag to execute & confirm the action.');

            return false;
        }

        $deleted = 0;
        $cleanUpSize = 0;
        
        foreach ($files as $file) {
            if (\file_exists($file->getPath())) {
                $this->style->writeLine('Deleting: '.$file->getPath());
                \unlink($file->getPath());

                $deleted++;
                $cleanUpSize += $file->getSize();
            }
        }

        $this->meta->set('deleted', $deleted);
        $this->meta->set('cleanUpSum', $cleanUpSize);

        $this->style->writeEmptyLine();
        $this->style->writeLine(\sprintf(' >> Deleted %s files.', $deleted));
        $this->style->writeLine(\sprintf(' >> Cleaned up %s B.', $deleted));

        return true;
    }

    protected function _getFileRepository(): FileRepository
    {
        return new FileRepository($this->_getMongoService());
    }
}