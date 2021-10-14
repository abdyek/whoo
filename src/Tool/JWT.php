<?php

namespace Abdyek\Whoo\Tool;
use Firebase\JWT\JWT as FirebaseJWT;
use Abdyek\Whoo\Config\JWT as JWTConfig;
use Abdyek\Whoo\Exception\InvalidTokenException;
use Abdyek\Whoo\Model\User;

class JWT {
    //const REGISTERED_CLAIM = ['iss', 'sub', 'aud', 'exp', 'nbf', 'iat', 'jti'];
    private static $secretKey = 's3cr3t';
    public static function generateToken($userId, $signOutCount) {
        /*
        foreach(self::REGISTERED_CLAIM as $claim) {

        }
         */
        return FirebaseJWT::encode([
            'iss' => JWTConfig::$iss,
            'sub' => JWTConfig::$sub,
            'aud' => JWTConfig::$aud,
            'exp' => JWTConfig::$exp,
            'nbf' => JWTConfig::$nbf,
            'iat' => JWTConfig::$iat,
            'jti' => JWTConfig::$jti,
            'whoo' => [
                'userId' => $userId,
            ],
            'signOutCount'=> $signOutCount
        ], self::$secretKey);
    }
    public static function getPayloadWithUser($jwt) {
        try {
            $payload = (array) FirebaseJWT::decode($jwt, JWT::getSecretKey(), array('HS256'));
            $user = User::getById($payload['whoo']->userId);
            if($payload['signOutCount']!=$user->getSignOutCount()) {
                throw new InvalidTokenException;
            }
            return [
                'payload'=>$payload,
                'user'=>$user
            ];
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
