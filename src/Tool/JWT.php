<?php

namespace Abdyek\Whoo\Tool;
use Firebase\JWT\JWT as FirebaseJWT;
use Abdyek\Whoo\Config\JWT as JWTConfig;
use Abdyek\Whoo\Exception\InvalidTokenException;
use Abdyek\Whoo\Model\User;

class JWT {
    const REGISTERED_CLAIM = ['iss', 'sub', 'aud', 'exp', 'nbf', 'iat', 'jti'];
    private static $additionalClaims = [];
    private static $secretKey = 's3cr3t';
    public static function generateToken($userId, $signOutCount) {
        $data = [];
        foreach(self::REGISTERED_CLAIM as $claim) {
            $val = JWTConfig::$$claim;
            if($val !== null) {
                $data[$claim] = $val;
            }
        }
        $data['whoo'] = [
            'userId' => $userId,
            'signOutCount'=> $signOutCount
        ];
        $data = array_merge($data, self::$additionalClaims);
        return FirebaseJWT::encode($data, self::$secretKey);
    }
    public static function getPayloadWithUser($jwt) {
        try {
            $payload = FirebaseJWT::decode($jwt, JWT::getSecretKey(), array('HS256'));
            $user = User::getById($payload->whoo->userId);
            if($payload->whoo->signOutCount!=$user->getSignOutCount()) {
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
    public static function setAdditionalClaims($additionalClaims) {
        self::$additionalClaims = $additionalClaims;
    }
}
