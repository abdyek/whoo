<?php

trait ChangeConfig {
    public static function changeConfig($newConf):array {
        $config = Abdyek\Whoo\Config\Whoo::CONFIG;
        foreach($newConf as $con=>$val) {
            $config[$con] = $val;
        }
        return $config;
    }
}

