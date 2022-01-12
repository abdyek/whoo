<?php

require __DIR__ . '/../vendor/autoload.php';

use Abdyek\Whoo\Command\Init;
use Abdyek\Whoo\Command\PropelCreate;
use Abdyek\Whoo\Command\PropelUpdate;
use Symfony\Component\Console\Application;

$cli = new Application();

$cli->add(new Init);
$cli->add(new PropelCreate);
$cli->add(new PropelUpdate);

$cli->run();
