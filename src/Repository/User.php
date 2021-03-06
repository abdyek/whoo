<?php

namespace Abdyek\Whoo\Repository;
use Abdyek\Whoo\Repository\AuthenticationCode;

class User {
    public static function isUniqueEmail($email) : bool {
        return (self::query()->findOneByEmail($email)?false:true);
    }
    public static function isUniqueUsername($username) : bool {
        return (self::query()->findOneByUsername($username)? false:true);
    }
    public static function create($args) {
        $user = new \User();
        $user->setEmail($args['email']);
        $user->setPasswordHash((isset($args['password'])?password_hash($args['password'], PASSWORD_DEFAULT):null));
        $user->setProvider((isset($args['provider'])?$args['provider']:null));
        $user->setProviderId((isset($args['providerId'])?$args['providerId']:null));
        $user->setEmailVerified(((isset($args['provider']) and isset($args['providerId']))?true:false));
        $user->setTwoFactorAuthentication((isset($args['twoFactorAuthentication'])?$args['twoFactorAuthentication']:false));
        $user->save();
        return $user;
    }
    public static function getByEmail($email) {
        return self::query()->findOneByEmail($email);
    }
    public static function getByUsername($username) {
        return self::query()->findOneByUsername($username);
    }
    public static function getById($id) {
        return self::query()->findPK($id);
    }
    public static function setUsername($user, $username) {
        $user->setUsername($username);
        $user->save();
    }
    public static function setEmailVerified($user, $value) {
        $user->setEmailVerified($value);
        $user->save();
    }
    public static function increaseSignOutCount($user) {
        $count = $user->getSignOutCount() + 1;
        $user->setSignOutCount($count);
        $user->save();
    }
    public static function setPassword($user, $newPassword) {
        $user->setPasswordHash(password_hash($newPassword, PASSWORD_DEFAULT));
        $user->save();
    }
    public static function checkPassword($user, $password) {
        return password_verify($password, $user->getPasswordHash());
    }
    public static function setEmail($user, $email) {
        $user->setEmail($email);
        $user->save();
    }
    public static function set2FA($user, $value) {
        $user->setTwoFactorAuthentication($value);
        $user->save();
    }
    public static function deleteWithAll($user) {
        AuthenticationCode::deleteAllByUserId($user->getId());
        $user->delete();
    }
    private static function query() {
        return \UserQuery::create();
    }
}
