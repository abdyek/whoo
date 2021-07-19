<?php

trait Reset {
    public static function reset(){
        $authCodes = AuthenticationCodeQuery::create()->find();
        $authCodes->delete();
        $members = MemberQuery::create()->find();
        $members->delete();
    }
}
