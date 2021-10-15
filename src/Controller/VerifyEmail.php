<?php

namespace Abdyek\Whoo\Controller;
use Abdyek\Whoo\Core\Controller;
use Abdyek\Whoo\Model\User;
use Abdyek\Whoo\Model\AuthenticationCode;
use Abdyek\Whoo\Exception\TimeOutCodeException;
use Abdyek\Whoo\Exception\InvalidCodeException;
use Abdyek\Whoo\Exception\TrialCountOverException;
use Abdyek\Whoo\Exception\NotFoundException;
use Abdyek\Whoo\Exception\NotFoundAuthCodeException;
use Abdyek\Whoo\Config\Authentication as AuthConfig;

class VerifyEmail extends Controller {
    protected function run() {
        $user = User::getByEmail($this->data['email']);
        if($user === null) {
            throw new NotFoundException;
        }
        $auth = AuthenticationCode::getByUserIdType($user->getId(), AuthConfig::TYPE_EMAIL_VERIFICATION);
        if($auth === null) {
            throw new NotFoundAuthCodeException;
        }
        if($auth->getTrialCount()+1>=AuthConfig::$TRIAL_MAX_COUNT) {
            throw new TrialCountOverException;
        }
        $dateTime = $auth->getDateTime();
        $timestamp = $dateTime->getTimestamp();
        if((time()-$timestamp)>AuthConfig::$VALIDITY_TIME) {
            throw new TimeOutCodeException;
        }
        if($auth->getCode()!==$this->data['code']) {
            AuthenticationCode::increaseTrialCount($auth);
            throw new InvalidCodeException;
        }
        User::setEmailVerified($user, true);
        AuthenticationCode::delete($auth);
    }
}
