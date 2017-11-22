<?php


if (is_file($autoloader = __DIR__ . "/vendor/autoload.php"))
{
    require_once $autoloader;
}
else if (is_file($autoloader = dirname(__DIR__, 2) . "/autoload.php"))
{
    require_once $autoloader;
}

use Symfony\Component\Console\Application;

$application = new Application();
$command = new \Becklyn\CertKeyChecker\CertCheckCommand();

$application->add($command);
$application->setDefaultCommand($command->getName());

$application->run();
