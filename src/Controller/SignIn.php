<?php

namespace Whoo\Controller;
use Whoo\Core\Controller;
use Whoo\Model\Member as MemberModel;
use Whoo\Exception\NotFoundException;
use Whoo\Exception\IncorrectPasswordException;
use Firebase\JWT\JWT;
use Whoo\Config\JWT as JWTConfig;

class SignIn extends Controller {
    public $jwt = null;
    public $user = null;
    protected function run() {
        $this->user = MemberModel::getByEmail($this->data['email']);
        if($this->user ===null) {
            throw new NotFoundException;
        }
        if($this->validateEmailPassword()===false) {
            throw new IncorrectPasswordException;
        }
        $this->jwt = JWT::encode([
            'iss' => JWTConfig::ISS,
            'aud' => JWTConfig::AUD,
            'iat' => JWTConfig::IAT,
            'nbf' => JWTConfig::NBF,
            'who' => $this->data['who'],
            'userId' => $this->user ->getId(),
        ], JWTConfig::SECRET_KEY);
        $this->setSuccess();
    }
    private function validateEmailPassword() {
        $pwHash = $this->user->getPasswordHash();
        return password_verify($this->data['password'], $pwHash);
    }
}
