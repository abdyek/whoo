<?php

namespace Abdyek\Whoo\Tool;
use Abdyek\Whoo\Tool\Propel;

class Config {
    public static $PROPEL_CONFIG_DIR = null;
    public static function load() {
        Propel::load();
        // I will move all config funcs under this class
    }
    public static function setPropelConfigDir($conf) {
        self::$PROPEL_CONFIG_DIR = $conf;
    }
}
