<?php

namespace Whoo\Controller;
use Firebase\JWT\JWT;
use Whoo\Core\Controller;
use Whoo\Model\User;
use Whoo\Model\AuthenticationCode;
use Whoo\Config\JWT as JWTConfig;
use Whoo\Config\Authentication as AuthConfig;
use Whoo\Tool\Random;
use Whoo\Exception\NotFoundException;
use Whoo\Exception\NotVerifiedEmailException;
use Whoo\Exception\IncorrectPasswordException;
use Whoo\Exception\TwoFactorAuthEnabledException;

class SignInByUsername extends Controller {
    public $jwt = null;
    public $code = null;
    protected function run() {
        $this->user = User::getByUsername($this->data['username']);
        if($this->user===null) {
            throw new NotFoundException;
        }
        $pwHash = $this->user->getPasswordHash();
        if(password_verify($this->data['password'], $pwHash)===false) {
            throw new IncorrectPasswordException;
        }
        if($this->config['DENY_IF_NOT_VERIFIED_TO_SIGN_IN'] and $this->user->getEmailVerified()===false) {
            throw new NotVerifiedEmailException;
        }
        if($this->user->getTwoFactorAuthentication()) {
            $this->code = Random::number(AuthConfig::SIZE_OF_CODE_FOR_2FA);
            AuthenticationCode::create($this->user->getId(), '2FA-sign-in', $this->code);
            $e = new TwoFactorAuthEnabledException;
            $e->setAuthenticationCode($this->code);
            throw $e;
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
