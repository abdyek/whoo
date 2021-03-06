<?php

use Abdyek\Whoo\Config\Propel as PropelConfig;

return [
    'propel' => [
        'database' => [
            'connections' => [
                'whoo' => [
                    'adapter'    => PropelConfig::$ADAPTER,
                    'classname'  => 'Propel\Runtime\Connection\ConnectionWrapper',
                    'dsn'        => PropelConfig::$DSN,
                    'user'       => PropelConfig::$USER,
                    'password'   => PropelConfig::$PASSWORD,
                    'attributes' => [],
                    'settings'   => [
                        'charset'=> 'utf8mb4',
                        'queries'=> [
                            'utf8' => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci, COLLATION_CONNECTION = utf8mb4_unicode_ci, COLLATION_DATABASE = utf8mb4_unicode_ci, COLLATION_SERVER = utf8mb4_unicode_ci'
                        ]
                    ]
                ]
            ]
        ],
        'runtime' => [
            'defaultConnection' => 'whoo',
            'connections' => ['whoo']
        ],
        'generator' => [
            'defaultConnection' => 'whoo',
            'connections' => ['whoo']
        ]
    ]
];
