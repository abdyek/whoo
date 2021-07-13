<?php
namespace Whoo\Core;

class Response {
    public static function success($data) {
        return [
            'status'=>'success',
            'data'=>$data
        ];
    }
    public static function error($code, $data=[]) {
        return [
            'status'=>'error',
            'code'=>$code,
            'data'=>$data
        ];
    }
}
