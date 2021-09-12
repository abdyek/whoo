<?php

namespace Abdyek\Whoo\Tool;
use Abdyek\Whoo\Config\Propel as PropelConf;
use Abdyek\Whoo\Tool\Config;

class Propel {
    public static function load() {
        if(Config::$PROPEL_CONFIG_DIR===null) {
            require PropelConf::CONFIG_DIR;
        } else {
            require Config::$PROPEL_CONFIG_DIR;
        }
    }
}
