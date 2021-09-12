<?php

namespace Abdyek\Whoo\Tool;
use Abdyek\Whoo\Config\Propel as PropelConf;

class Propel {
    public static function setConfig() {
        require PropelConf::CONFIG_DIR;
    }
}
