<?php

namespace Abdyek\Whoo\Tool;
use Firebase\JWT\JWT as FirebaseJWT;
use Abdyek\Whoo\Config\JWT as JWTConfig;
use Abdyek\Whoo\Exception\InvalidTokenException;

class JWT {
    private static $secretKey = 's3cr3t';
    public static function generateToken($userId, $signOutCount) {
        return FirebaseJWT::encode([
            'iss' => JWTConfig::$ISS,
            'aud' => JWTConfig::$AUD,
            'iat' => JWTConfig::$IAT,
            'nbf' => JWTConfig::$NBF,
            'userId' => $userId,
            'signOutCount'=> $signOutCount
        ], self::$secretKey);
    }
    public static function getPayload($jwt) {
        try {
            return (array) FirebaseJWT::decode($jwt, JWT::getSecretKey(), array('HS256'));
        } catch (\UnexpectedValueException $e) {
            throw new InvalidTokenException;
        }
    }
    public static function setSecretKey($secretKey) {
        self::$secretKey = $secretKey;
    }
    public static function getSecretKey() {
        return self::$secretKey;
    }
}
