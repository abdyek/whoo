<?php

require 'vendor/autoload.php';
use Abdyek\Whoo\Tool\CLI;

$cli = new \Commando\Command();
$cli->option('i')->aka('init')->boolean();

if($cli['init']) {
    CLI::init();
}
