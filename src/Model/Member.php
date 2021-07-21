<?php

namespace Whoo\Model;

class Member {
    public static function isUniqueEmail($email) : bool {
        return (self::query()->findOneByEmail($email)?false:true);
    }
    public static function isUniqueUsername($username) : bool {
        return (self::query()->findOneByUsername($username)? false:true);
    }
    public static function create($args) {
        $member = new \Member();
        $member->setEmail($args['email']);
        $member->setUsername($args['username']);
        $member->setPasswordHash((isset($args['password'])?password_hash($args['password'], PASSWORD_DEFAULT):null));
        $member->setProvider((isset($args['provider'])?$args['provider']:null));
        $member->setProviderId((isset($args['providerId'])?$args['providerId']:null));
        $member->setEmailVerified(((isset($args['provider']) and isset($args['providerId']))?true:false));
        $member->save();
        return $member;
    }
    public static function getByEmail($email) {
        return self::query()->findOneByEmail($email);
    }
    private static function query() {
        return \MemberQuery::create();
    }
}
