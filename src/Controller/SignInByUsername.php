<?php

namespace Whoo\Controller;
use Firebase\JWT\JWT;
use Whoo\Core\Controller;
use Whoo\Model\Member;
use Whoo\Config\Whoo;
use Whoo\Config\JWT as JWTConfig;
use Whoo\Exception\NotFoundException;
use Whoo\Exception\NotVerifiedEmailException;
use Whoo\Exception\IncorrectPasswordException;

class SignInByUsername extends Controller {
    public $jwt = null;
    protected function run() {
        $this->user = Member::getByUsername($this->data['username']);
        if($this->user===null) {
            throw new NotFoundException;
        }
        $pwHash = $this->user->getPasswordHash();
        if(password_verify($this->data['password'], $pwHash)===false) {
            throw new IncorrectPasswordException;
        }
        if(Whoo::BLOCK_NOT_VERIFIED and $this->user->getEmailVerified()===false) {
            throw new NotVerifiedEmailException;
        }
        $this->jwt = JWT::encode([
            'iss' => JWTConfig::ISS,
            'aud' => JWTConfig::AUD,
            'iat' => JWTConfig::IAT,
            'nbf' => JWTConfig::NBF,
            'userId' => $this->user->getId(),
        ], JWTConfig::SECRET_KEY);
    }
}
