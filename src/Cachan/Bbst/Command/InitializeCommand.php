<?php

namespace Cachan\Bbst\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class InitializeCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('initialize')
            ->setDescription('Initialize your environment')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fs = new Filesystem\Filesystem();
        $root = getcwd();

        try {
            $fs->mirror($root . "/src/Cachan/Bbst/Resources/samples", 'features');
        }
        catch (IOExceptionInterface $e) {
            $output->writeln("An error occurred while mirror the directory".$e->getMessage());
        }
    }
}
