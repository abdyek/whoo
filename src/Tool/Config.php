<?php

namespace Abdyek\Whoo\Tool;
use Abdyek\Whoo\Config\Propel as PropelConfig;
use Abdyek\Whoo\Config\Whoo as WhooConfig;

class Config {
    public static function load($configFile=null) {
        if($configFile!==null) {
            WhooConfig::$CONFIG_FILE = $configFile;
        }
        require PropelConfig::$CONFIG_FILE;
        require WhooConfig::$CONFIG_FILE;
    }
    public static function generateWhooConfigString($config) {
        $content = '<?php' . PHP_EOL . PHP_EOL;
        $types = ['authentication', 'jwt', 'whoo'];
        foreach($config as $key=>$attributes) {
            if($key==='jwt') {
                $type = strtoupper($key);
            } elseif(in_array($key, $types)) {
                $type = ucfirst($key);
            } else {
                continue;
            }
            $name = 'Abdyek\\Whoo\\Config\\' . $type;
            foreach($attributes as $con=>$val) {
                $attr = strtoupper($con);
                if($name::$$attr!==$val) {
                    if(is_bool($val)) {
                        $val = ($val===true)?'true': 'false';
                    }
                    $content .= $name . '::$' . $attr . ' = ' . $val . ';' . PHP_EOL;
                }
            }
        }
        return $content;
    }
}
