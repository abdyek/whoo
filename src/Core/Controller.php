<?php
namespace Whoo\Core;
use Firebase\JWT\JWT;
use Whoo\Core\Response;
use Whoo\Config\JWT as JWTConfig;

class Controller {
    public $response;
    protected $userId = null;
    protected $who = 'guest';
    public function __construct($data) {
        $this->data = $data;
        $this->detectUser();
        $this->run();
    }
    private function detectUser() {
        if(isset($this->data['jwt'])) {
            try {
                $userInfo = (array) JWT::decode($this->data['jwt'], JWTConfig::SECRET_KEY, array('HS256'));
                $this->userId = $userInfo['userId'];
                $this->who = $userInfo['who'];
            } catch (\Firebase\JWT\ExpiredException $e) {
                // nothing
            } catch (\Firebase\JWT\SignatureInvalidException $e) {
                // nothing
            }
        }
    }
    protected function setResponseData($data) {
        $this->response = Response::success($data);
    }
    protected function setError($code, $data=[]) {
        $this->response = Response::error($code, $data);
    }
}
