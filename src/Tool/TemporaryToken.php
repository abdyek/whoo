<?php

namespace Whoo\Tool;
use Whoo\Config\Env;

class TemporaryToken {
    const SEPARATOR = 't';
    public static function generate($userId) {
        $idLen = strlen((string)$userId);
        $string = $userId . Env::TEMPORARY_TOKEN_SECRET_KEY . $userId;
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
