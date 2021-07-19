<?php

namespace Whoo\Model;

class Member {
    public static function isUniqueEmail($email) : bool {
        return (self::query()->findOneByEmail($email)?false:true);
    }
    public static function isUniqueUsername($username) : bool {
        return (self::query()->findOneByUsername($username)? false:true);
    }
    private static function query() {
        return \MemberQuery::create();
    }
}
