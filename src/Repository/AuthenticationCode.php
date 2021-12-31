<?php

namespace Abdyek\Whoo\Repository;

class AuthenticationCode {
    public static function create($userId, $type, $code) {
        // will be completed
        $authCode = new \AuthenticationCode();
        $authCode->setUserId($userId);
        $authCode->setType($type);
        $authCode->setCode($code);
        $authCode->setDateTime(time());
        $authCode->save();
        return $authCode;
    }
    public static function getByUserIdType($userId, $type) {
        return self::query()->filterByUserId($userId)->findOneByType($type);
    }
    public static function getAllByUserId($userId) {
        return self::query()->findByUserId($userId);
    }
    public static function increaseTrialCount($auth) {
        $count = 1 + $auth->getTrialCount();
        $auth->setTrialCount($count);
        $auth->save();
        return $auth;
    }
    public static function delete($auth) {
        $auth->delete();
    }
    public static function deleteByUserIdType($userId, $type) {
        $auth = self::getByUserIdType($userId, $type);
        if($auth)
            $auth->delete();
    }
    public static function deleteAllByUserId($userId) {
        $auths = self::getAllByUserId($userId);
        $auths->delete();
    }
    private static function query() {
        return \AuthenticationCodeQuery::create();
    }
}
