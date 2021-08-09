<?php

trait UserTool {
    public static $traitEmail = 'email@email.com';
    public static $traitUsername = 'this is username';
    public static function createExample($args=null) {
        $email = ($args!==null?$args['email']: self::$traitEmail);
        $username = ($args!==null?$args['username']: self::$traitUsername);
        $user = new \User;
        $user->setEmail($email);
        $user->setUsername($username);
        $user->save();
        return $user;
    }
}
