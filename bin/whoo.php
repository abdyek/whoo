<?php

require 'vendor/autoload.php';
use Abdyek\Whoo\Tool\CLI;

if(in_array('init', $_SERVER['argv'])) {
    CLI::init();
} elseif(in_array('update-config', $_SERVER['argv'])) {
    CLI::updateConfig();
}
