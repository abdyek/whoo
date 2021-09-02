<?php

namespace Abdyek\Whoo\Controller;
use Abdyek\Whoo\Core\Controller;
use Abdyek\Whoo\Model\User;
use Abdyek\Whoo\Model\AuthenticationCode;
use Abdyek\Whoo\Exception\NotFoundException;
use Abdyek\Whoo\Exception\IncorrectPasswordException;
use Abdyek\Whoo\Exception\NullUsernameException;
use Abdyek\Whoo\Exception\NotVerifiedEmailException;
use Abdyek\Whoo\Exception\TwoFactorAuthEnabledException;
use Firebase\JWT\JWT;
use Abdyek\Whoo\Config\JWT as JWTConfig;
use Abdyek\Whoo\Config\Authentication as AuthConfig;
use Abdyek\Whoo\Tool\TemporaryToken;
use Abdyek\Whoo\Tool\Random;

class SignIn extends Controller {
    public $jwt = null;
    public $user = null;
    public $temporaryToken = null;
    protected function run() {
        $this->user = User::getByEmail($this->data['email']);
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
        if($this->user->getTwoFactorAuthentication()) {
            AuthenticationCode::deleteByUserIdType($this->user->getId(), AuthConfig::TYPE_2FA);
            $this->code = Random::number(AuthConfig::SIZE_OF_CODE_FOR_2FA);
            AuthenticationCode::create($this->user->getId(), AuthConfig::TYPE_2FA, $this->code);
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
            'signOutCount'=> $this->user->getSignOutCount()
        ], JWTConfig::SECRET_KEY);
    }
    private function validateEmailPassword() {
        $pwHash = $this->user->getPasswordHash();
        return password_verify($this->data['password'], $pwHash);
    }
}
