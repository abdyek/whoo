<?php

namespace Abdyek\Whoo\Controller;
use Abdyek\Whoo\Core\Controller;
use Abdyek\Whoo\Model\User;
use Abdyek\Whoo\Model\AuthenticationCode;
use Abdyek\Whoo\Config\JWT as JWTConfig;
use Abdyek\Whoo\Config\Authentication as AuthConfig;
use Abdyek\Whoo\Tool\Random;
use Abdyek\Whoo\Exception\NotFoundException;
use Abdyek\Whoo\Exception\NotVerifiedEmailException;
use Abdyek\Whoo\Exception\IncorrectPasswordException;
use Abdyek\Whoo\Exception\TwoFactorAuthEnabledException;
use Abdyek\Whoo\Tool\JWT;

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
            AuthenticationCode::deleteByUserIdType($this->user->getId(), AuthConfig::TYPE_2FA);
            $this->code = Random::number(AuthConfig::SIZE_OF_CODE_FOR_2FA);
            AuthenticationCode::create($this->user->getId(), AuthConfig::TYPE_2FA, $this->code);
            $e = new TwoFactorAuthEnabledException;
            $e->setAuthenticationCode($this->code);
            throw $e;
        }
        $this->jwt = JWT::generateToken($this->user->getId(), $this->user->getSignOutCount());
    }
}
