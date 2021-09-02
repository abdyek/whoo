<?php

namespace Abdyek\Whoo\Controller;
use Firebase\JWT\JWT;
use Abdyek\Whoo\Core\Controller;
use Abdyek\Whoo\Model\User as UserModel;
use Abdyek\Whoo\Config\JWT as JWTConfig;
use Abdyek\Whoo\Exception\SignUpByEmailException;
use Abdyek\Whoo\Exception\NullUsernameException;

class SignInByProvider extends Controller {
    public $registering = false;
    public $jwt = null;
    protected function run() {
        $user = UserModel::getByEmail($this->data['email']);
        if($user===null) {
            $user = UserModel::create($this->data);
            $this->registering = true;
        } else if($this->config['DENY_IF_SIGN_UP_BEFORE_BY_EMAIL'] and $user->getProvider()===null) {
            throw new SignUpByEmailException;
        }
        $user = UserModel::getByEmail($this->data['email']);
        if($this->config['USE_USERNAME'] and $user->getUsername()===null) {
            throw new NullUsernameException;
        }
        $this->jwt = JWT::encode([
            'iss' => JWTConfig::ISS,
            'aud' => JWTConfig::AUD,
            'iat' => JWTConfig::IAT,
            'nbf' => JWTConfig::NBF,
            'userId' => $user->getId(),
        ], JWTConfig::SECRET_KEY);
    }
}
