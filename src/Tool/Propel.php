<?php

namespace Abdyek\Whoo\Tool;
use Abdyek\Whoo\Config\Propel as PropelConfig;

class Propel {
    public static function setConfig($config) {
        if(isset($config['database'])) {
            $database = $config['database'];
            if(isset($database['adapter'])) {
                PropelConfig::$ADAPTER = $database['adapter'];
            }
            if(isset($adapter['dsn'])) {
                PropelConfig::$DSN = $database['dsn'];
            }
            if(isset($database['host'])) {
                PropelConfig::$HOST = $database['host'];
            }
            if(isset($adapter['dbname'])) {
                PropelConfig::$DBNAME = $database['dbname'];
            }
            if(isset($database['user'])) {
                PropelConfig::$USER = $database['user'];
            }
            if(isset($database['password'])) {
                PropelConfig::$PASSWORD = $database['password'];
            }
        }
    }
}
