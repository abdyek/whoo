<?php

trait Reset {
    public static function reset(){
        $authCodes = AuthenticationCodeQuery::create()->find();
        $authCodes->delete();
        $users = UserQuery::create()->find();
        $users->delete();
    }
}
