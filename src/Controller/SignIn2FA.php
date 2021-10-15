<?php

namespace Abdyek\Whoo\Controller;
use Abdyek\Whoo\Core\Controller;
use Abdyek\Whoo\Model\AuthenticationCode;
use Abdyek\Whoo\Model\User;
use Abdyek\Whoo\Exception\NotFoundException;
use Abdyek\Whoo\Exception\NotFoundAuthCodeException;
use Abdyek\Whoo\Exception\TrialCountOverException;
use Abdyek\Whoo\Exception\InvalidCodeException;
use Abdyek\Whoo\Config\Authentication as AuthConfig;
use Abdyek\Whoo\Config\JWT as JWTConfig;
use Abdyek\Whoo\Tool\JWT;

class SignIn2FA extends Controller {
    public $jwt = null;
    protected function run() {
        $this->user = User::getByEmail($this->data['email']);
        if(!$this->user) {
            throw new NotFoundException;
        }
        $auth = AuthenticationCode::getByUserIdType($this->user->getId(), AuthConfig::TYPE_2FA);
        if(!$auth) {
            throw new NotFoundAuthCodeException;
        }
        if($auth->getTrialCount()+1>=AuthConfig::$TRIAL_MAX_COUNT_TO_SIGN_IN_2FA) {
            throw new TrialCountOverException;
        }
        $dateTime = $auth->getDateTime();
        $timestamp = $dateTime->getTimestamp();
        if((time()-$timestamp)>AuthConfig::$VALIDITY_TIME_TO_SIGN_IN_2FA) {
            throw new TimeOutCodeException;
        }
        if($auth->getCode()!==$this->data['authenticationCode']) {
            AuthenticationCode::increaseTrialCount($auth);
            throw new InvalidCodeException;
        }
        $this->jwt = JWT::generateToken($this->user->getId(), $this->user->getSignOutCount());
        AuthenticationCode::delete($auth);
    }
}
