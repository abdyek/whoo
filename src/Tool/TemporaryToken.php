<?php

namespace Abdyek\Whoo\Tool;
use Abdyek\Whoo\Tool\JWT as JWTTool;

class TemporaryToken {
    const SEPARATOR = 't';
    public static function generate($userId) {
        $idLen = strlen((string)$userId);
        $string = $userId . JWTTool::getSecretKey() . $userId;
        $rawHash = hash('sha256', $string);
        $tempToken = $userId . self::SEPARATOR . substr($rawHash, 0, 59-$idLen);
        return $tempToken;
    }
    public static function getUserId($tempToken) {
        $exploded = explode(self::SEPARATOR, $tempToken);
        if(count($exploded)===1) {
            return null;
        }
        $userId = $exploded[0];
        $generated = self::generate((int)$userId);
        if($generated!==$tempToken) {
            return null;
        }
        return $userId;
    }
}
