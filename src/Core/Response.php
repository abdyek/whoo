<?php
namespace Whoo\Core;

class Response {
    public static function success($data) {
        return [
            'status'=>'success',
            'data'=>$data
        ];
    }
}
