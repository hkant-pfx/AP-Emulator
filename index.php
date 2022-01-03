<?php 

require __DIR__.'/vendor/autoload.php';



use Symfony\Component\Console\Application;

$application = new Application();

// ... register commands

$application->add(new App\Command\ColdTray());
$application->add(new App\Command\HotTray());
$application->add(new App\Command\Switchboard());
$application->add(new App\Command\OvenTemp());

$application->add(new App\Command\PaymentServer());


$application->run();