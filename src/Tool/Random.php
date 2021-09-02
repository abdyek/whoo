<?php

namespace Abdyek\Whoo\Tool;

class Random {
    private static $raw = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    public static function number(int $size): string {
        $min = '1';
        $max = '9';
        for($i=1;$i<$size;$i++) {
            $min .= '0';
            $max .= '9';
        }
        $randomInt = random_int((int)$min, (int)$max);
        return (string)$randomInt;
    }
    public static function chars(int $size): string {
        $chars = '';
        $length = strlen(self::$raw);
        for($i=0; $i<$size; $i++) {
            $chars .= self::$raw[random_int(0, $length-1)];
        }
        return $chars;
    }
}
