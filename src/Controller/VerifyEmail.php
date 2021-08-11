<?php

namespace Whoo\Controller;
use Whoo\Core\Controller;
use Whoo\Model\User;
use Whoo\Model\AuthenticationCode;
use Whoo\Exception\TimeOutCodeException;
use Whoo\Exception\InvalidCodeException;
use Whoo\Exception\TrialCountOverException;
use Whoo\Config\Authentication as AuthConfig;

class VerifyEmail extends Controller {
    protected function run() {
        $user = User::getByEmail($this->data['email']);
        if($user === null) {
            throw new InvalidCodeException;
        }
        $auth = AuthenticationCode::getByUserIdType($user->getId(), 'emailVerification');
        if($auth === null) {
            throw new InvalidCodeException;
        }
        if($auth->getTrialCount()+1>=AuthConfig::TRIAL_MAX_COUNT) {
            throw new TrialCountOverException;
        }
        $dateTime = $auth->getDateTime();
        $timestamp = $dateTime->getTimestamp();
        if((time()-$timestamp)>AuthConfig::VALIDITY_TIME) {
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
