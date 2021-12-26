<?php

trait Reset {
    public static function reset(){
        require 'generated-conf/config.php';
        $authCodes = AuthenticationCodeQuery::create()->find();
        $authCodes->delete();
        $users = UserQuery::create()->find();
        $users->delete();
    }
}
