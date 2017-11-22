<?php


if (is_file($autoloader = __DIR__ . "/vendor/autoload.php"))
{
    require $autoloader;
}

use Symfony\Component\Console\Application;

$application = new Application();
$command = new \Becklyn\CertKeyChecker\CertCheckCommand();

$application->add($command);
$application->setDefaultCommand($command->getName());

$application->run();
