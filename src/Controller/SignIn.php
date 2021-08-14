<?php

namespace Whoo\Controller;
use Whoo\Core\Controller;
use Whoo\Model\User as UserModel;
use Whoo\Exception\NotFoundException;
use Whoo\Exception\IncorrectPasswordException;
use Firebase\JWT\JWT;
use Whoo\Config\JWT as JWTConfig;
use Whoo\Tool\TemporaryToken;

class SignIn extends Controller {
    public $jwt = null;
    public $user = null;
    public $temporaryToken = null;
    protected function run() {
        $this->user = UserModel::getByEmail($this->data['email']);
        if($this->user ===null) {
            throw new NotFoundException;
        }
        if($this->validateEmailPassword()===false) {
            throw new IncorrectPasswordException;
        }
        if($this->user->getUsername()===null) {
            $this->temporaryToken = TemporaryToken::generate($this->user->getId());
        }
        $this->jwt = JWT::encode([
            'iss' => JWTConfig::ISS,
            'aud' => JWTConfig::AUD,
            'iat' => JWTConfig::IAT,
            'nbf' => JWTConfig::NBF,
            'userId' => $this->user->getId(),
            'signOutCount'=> $this->user->getSignOutCount()
        ], JWTConfig::SECRET_KEY);
    }
    private function validateEmailPassword() {
        $pwHash = $this->user->getPasswordHash();
        return password_verify($this->data['password'], $pwHash);
    }
}
