<?php

require 'vendor/autoload.php';
use Abdyek\Whoo\Tool\CLI;

if(in_array('init', $_SERVER['argv'])) {
    $config = (file_exists('whoo.json')?'whoo.json': null);
    CLI::init($config);
} elseif(in_array('update-config', $_SERVER['argv'])) {
    CLI::updateConfig();
}
