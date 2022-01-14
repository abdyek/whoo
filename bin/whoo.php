<?php

require 'vendor/autoload.php';

use Abdyek\Whoo\Command\Init;
use Abdyek\Whoo\Command\PropelCreate;
use Abdyek\Whoo\Command\PropelUpdate;
use Symfony\Component\Console\Application;

$cli = new Application();

function detectEnv()
{
    $path = exec('pwd') . '/composer.json';
    $content = json_decode(file_get_contents($path), true);
    $env = ($content['name'] === 'abdyek/whoo' ? 'dev' : 'prod');
    define('APP', $env);
}

detectEnv();

$cli->add(new Init);
$cli->add(new PropelCreate);
$cli->add(new PropelUpdate);

$cli->run();
