<?php

namespace Whoo\Model;

class AuthenticationCode {
    public static function create($userId, $type, $code) {
        // will be completed
        $authCode = new \AuthenticationCode();
        $authCode->setUserId($userId);
        $authCode->setType($type);
        $authCode->setCode($code);
        $authCode->save();
        return $authCode;
    }
    public static function getByUserIdType($userId, $type) {
        return self::query()->filterByUserId($userId)->findOneByType($type);
    }
    private static function query() {
        return \AuthenticationCodeQuery::create();
    }
}
