<?php

namespace Abdyek\Whoo\Tool;
use Abdyek\Whoo\Tool\Propel as PropelTool;
use Abdyek\Whoo\Tool\Config as ConfigTool;
use Abdyek\Whoo\Config\Propel as PropelConfig;

class CLI {
    private static $config = 'vendor/abdyek/whoo/whoo.json';
    private static $outputDir = 'whoo';
    public static function init($config=null, $outputDir=null) {
        self::$config = $config ?? self::$config;
        self::$outputDir = $outputDir ?? self::$outputDir;
        self::generateConfig();
    }
    public static function updateConfig($config = null, $outputDir = null) {
        self::$config = $config ?? self::$config;
        self::$outputDir = $outputDir ?? self::$outputDir;
        self::generateWhooConfig();
    }
    private static function generateConfig() {
        if(!file_exists(self::$outputDir)) {
            mkdir(self::$outputDir);
        }
        if(!file_exists(self::$outputDir . '/propel')) {
            mkdir(self::$outputDir. '/propel');
        }
        copy('vendor/abdyek/whoo/schema.xml', self::$outputDir . '/propel/schema.xml');
        
        self::setPropelConfigFromCustomConfig();
        self::generatePropelConfig();
        self::generateWhooConfig();
        
        // sql build
        shell_exec('vendor/bin/propel sql:build --config-dir="'.self::$outputDir.'/propel.json" --schema-dir="'.self::$outputDir.'/propel'.'" --output-dir="'.self::$outputDir.'/propel"');
        // model build
        shell_exec('vendor/bin/propel model:build --config-dir="'.self::$outputDir.'/propel.json" --schema-dir="'.self::$outputDir.'/propel'. '" --output-dir="'.self::$outputDir.'/propel/model"');
        // config convert
        shell_exec('vendor/bin/propel config:convert --config-dir="'.self::$outputDir.'/propel.json" --output-dir="'.self::$outputDir.'/propel"');

    }
    private static function setPropelConfigFromCustomConfig() {
        if(file_exists(self::$config)) {
            $config = json_decode(file_get_contents(self::$config),TRUE);
            PropelTool::setConfig($config);
        }
    }
    private static function generatePropelConfig() {
        $content = [
            'propel' => [
                'database' => [
                    'connections' => [
                        PropelConfig::$DBNAME => [
                            'adapter'    => PropelConfig::$ADAPTER,
                            'classname'  => 'Propel\Runtime\Connection\ConnectionWrapper',
                            'dsn'        => PropelConfig::$DSN,
                            'user'       => PropelConfig::$USER,
                            'password'   => PropelConfig::$PASSWORD,
                            'attributes' => []
                        ]
                    ]
                ],
                'runtime' => [
                    'defaultConnection' => PropelConfig::$DBNAME,
                    'connections' => [PropelConfig::$DBNAME]
                ],
                'generator' => [
                    'defaultConnection' => PropelConfig::$DBNAME,
                    'connections' => [PropelConfig::$DBNAME]
                ]
            ]
        ];
        $ADAPTER = PropelConfig::$ADAPTER;
        if(PropelConfig::$UTF8===true) {
            $settings = [];
            if($ADAPTER==='mysql') {
                $settings = [
                    'charset'=> 'utf8mb4',
                    'queries'=> [
                        'utf8' => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci, COLLATION_CONNECTION = utf8mb4_unicode_ci, COLLATION_DATABASE = utf8mb4_unicode_ci, COLLATION_SERVER = utf8mb4_unicode_ci'
                    ]
                ];
            } elseif($ADAPTER==='pgsql') {
                $settings = [
                    'charset'=>'utf8',
                    'queries'=> [
                        'utf8'=> "SET NAMES 'UTF8'"
                    ]
                ];
            } elseif($ADAPTER==='sqlite') {
                $settings = [
                    'charset'=>'utf8'
                ];
            } elseif($ADAPTER==='mssql') {
                $settings = [
                    'charset'=>'UTF-8'
                ];
            } elseif($ADAPTER==='oracle') {
                $settings = [
                    'charset'=>'UTF8'
                ];
            };
            $content['propel']['database']['connections'][PropelConfig::$DBNAME]['settings'] = $settings;
        }
        file_put_contents(self::$outputDir.'/propel.json', json_encode($content));
    }
    private static function generateWhooConfig() {
        if(!file_exists(self::$config)) {
            return;
        }
        $config = json_decode(file_get_contents(self::$config),TRUE);
        $string = ConfigTool::generateWhooConfigString($config);
        $configFile = fopen(self::$outputDir . '/config.php', "w");
        fwrite($configFile, $string);
        fclose($configFile);
    }
}
