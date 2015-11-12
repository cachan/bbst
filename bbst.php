#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';

use Cachan\Bbst\Command\InitializeCommand;
use Cachan\Bbst\Command\RunCommand;
use Symfony\Component\Console\Application;

$application = new Application();

$initializeCommand = new InitializeCommand();
$runCommand = new RunCommand();

$application->add(new $initializeCommand);
$application->add(new $runCommand);
$application->setDefaultCommand($runCommand->getName());
$application->run();