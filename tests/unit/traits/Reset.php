<?php

trait Reset {
    public static function reset(){
        require Abdyek\Whoo\Config\Propel::$CONFIG_FILE;
        $authCodes = AuthenticationCodeQuery::create()->find();
        $authCodes->delete();
        $users = UserQuery::create()->find();
        $users->delete();
    }
}
