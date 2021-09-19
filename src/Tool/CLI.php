<?php

namespace Abdyek\Whoo\Tool;
use Abdyek\Whoo\Tool\Config;

class CLI {
    private static $config;
    private static $outputDir;
    public static function init($config='whoo.json', $outputDir='whoo') {
        self::$config = ($config!==null?$config:'whoo.json');
        self::$outputDir = ($outputDir!==null?$outputDir:'whoo');
        self::generateConfig();
    }
    private static function generateConfig() {
        if(!file_exists(self::$outputDir)) {
            mkdir(self::$outputDir);
        }
        if(!file_exists(self::$outputDir . '/propel')) {
            mkdir(self::$outputDir. '/propel');
        }
        copy('vendor/abdyek/whoo/propel/schema.xml', self::$outputDir . '/propel/schema.xml');
        
        // sql build
        shell_exec('vendor/bin/propel sql:build --config-dir="vendor/abdyek/whoo/propel.php" --schema-dir="'.self::$outputDir.'/propel'.'" --output-dir="'.self::$outputDir.'/propel"');
        // model build
        shell_exec('vendor/bin/propel model:build --config-dir="vendor/abdyek/whoo/propel.php" --schema-dir="'.self::$outputDir.'/propel'. '" --output-dir="'.self::$outputDir.'/propel/model"');
        // config convert
        shell_exec('vendor/bin/propel config:convert --config-dir="vendor/abdyek/whoo/propel.php" --output-dir="'.self::$outputDir.'/propel"');

    }
}
