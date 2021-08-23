<?php

namespace Whoo\Controller;
use Whoo\Core\Controller;
use Whoo\Model\User as UserModel;
use Whoo\Exception\NotFoundException;
use Whoo\Exception\IncorrectPasswordException;
use Whoo\Exception\NullUsernameException;
use Whoo\Exception\NotVerifiedEmailException;
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
        if($this->config['DENY_IF_NOT_VERIFIED_TO_SIGN_IN'] and $this->user->getEmailVerified()===false) {
            throw new NotVerifiedEmailException;
        }
        if($this->config['USE_USERNAME'] and $this->user->getUsername()===null) {
            $this->temporaryToken = TemporaryToken::generate($this->user->getId());
            if($this->config['DENY_IF_NOT_SET_USERNAME']) {
                throw new NullUsernameException;
            }
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
