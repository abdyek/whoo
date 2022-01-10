<?php

namespace Abdyek\Whoo\Tool;

class TemporaryToken
{
    const SEPARATOR = 't';

    public static function generate(int $userId, string $secretKey): string
    {
        $idLen = strlen((string)$userId);
        $string = $userId . $secretKey . $userId;
        $rawHash = hash('sha256', $string);
        $tempToken = $userId . self::SEPARATOR . substr($rawHash, 0, 59-$idLen);
        return $tempToken;
    }

    public static function getUserId(string $tempToken, string $secretKey): ?int
    {
        $exploded = explode(self::SEPARATOR, $tempToken);
        if(count($exploded) === 1) {
            return null;
        }
        $userId = $exploded[0];
        $generated = self::generate((int) $userId, $secretKey);
        if($generated !== $tempToken) {
            return null;
        }
        return $userId;
    }
}
