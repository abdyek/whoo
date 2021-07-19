<?php

trait MemberTool {
    public static $traitEmail = 'email@email.com';
    public static $traitUsername = 'this is username';
    public static function createExample($args=null) {
        $email = ($args!==null?$args['email']: self::$traitEmail);
        $username = ($args!==null?$args['username']: self::$traitUsername);
        $member = new \Member;
        $member->setEmail($email);
        $member->setUsername($username);
        $member->save();
        return $member;
    }
}
