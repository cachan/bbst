<?php

namespace Cachan\Bbst\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use \Symfony\Component\Console\Input\ArgvInput;

class RunCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('run')
            ->setDescription('Run tests')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $factory = new \Behat\Behat\ApplicationFactory();
        $factory->createApplication()->run();
    }
}
