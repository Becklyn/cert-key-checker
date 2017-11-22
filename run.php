<?php

require __DIR__ . "/vendor/autoload.php";

use Symfony\Component\Console\Application;

$application = new Application();
$command = new \Becklyn\CertKeyChecker\CertCheckCommand();

$application->add($command);
$application->setDefaultCommand($command->getName());

$application->run();
