<?php

namespace Whoo\Controller;
use Firebase\JWT\JWT;
use Whoo\Core\Controller;
use Whoo\Model\User;
use Whoo\Model\AuthenticationCode;
use Whoo\Exception\NotFoundException;
use Whoo\Exception\NotFoundAuthCodeException;
use Whoo\Exception\TrialCountOverException;
use Whoo\Exception\InvalidCodeException;
use Whoo\Config\Authentication as AuthConfig;
use Whoo\Config\JWT as JWTConfig;

class SignInByUsername2FA extends Controller {
    public $jwt = null;
    protected function run() {
        $this->user = User::getByUsername($this->data['username']);
        if(!$this->user) {
            throw new NotFoundException;
        }
        $auth = AuthenticationCode::getByUserIdType($this->user->getId(), '2FA-sign-in');
        if(!$auth) {
            throw new NotFoundAuthCodeException;
        }
        if($auth->getTrialCount()+1>=AuthConfig::TRIAL_MAX_COUNT_TO_SIGN_IN_2FA) {
            throw new TrialCountOverException;
        }
        $dateTime = $auth->getDateTime();
        $timestamp = $dateTime->getTimestamp();
        if((time()-$timestamp)>AuthConfig::VALIDITY_TIME_TO_SIGN_IN_2FA) {
            throw new TimeOutCodeException;
        }
        if($auth->getCode()!==$this->data['authenticationCode']) {
            AuthenticationCode::increaseTrialCount($auth);
            throw new InvalidCodeException;
        }
        $this->jwt = JWT::encode([
            'iss' => JWTConfig::ISS,
            'aud' => JWTConfig::AUD,
            'iat' => JWTConfig::IAT,
            'nbf' => JWTConfig::NBF,
            'userId' => $this->user->getId(),
        ], JWTConfig::SECRET_KEY);
        AuthenticationCode::delete($auth);
    }
}
