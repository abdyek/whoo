<?php

namespace Whoo\Controller;
use Firebase\JWT\JWT;
use Whoo\Core\Controller;
use Whoo\Model\Member as MemberModel;
use Whoo\Config\Whoo as Config;
use Whoo\Config\JWT as JWTConfig;
use Whoo\Exception\SignUpByEmailException;
use Whoo\Exception\NullUsernameException;

class SignInByProvider extends Controller {
    public $registering = false;
    public $jwt = null;
    protected function run() {
        $user = MemberModel::getByEmail($this->data['email']);
        if($user===null) {
            $user = MemberModel::create($this->data);
            $this->registering = true;
        } else if(Config::BLOCK_IF_SIGN_UP_BEFORE_BY_EMAIL and $user->getProvider()===null) {
            throw new SignUpByEmailException;
        }
        $user = MemberModel::getByEmail($this->data['email']);
        if(Config::USE_USERNAME and $user->getUsername()===null) {
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
