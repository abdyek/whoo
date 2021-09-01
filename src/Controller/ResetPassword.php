<?php

namespace Whoo\Controller;
use Whoo\Core\Controller;
use Whoo\Model\User;
use Whoo\Model\AuthenticationCode;
use Whoo\Exception\NotFoundException;
use Whoo\Exception\NotFoundAuthCodeException;
use Whoo\Exception\InvalidCodeException;
use Whoo\Exception\NotVerifiedEmailException;
use Whoo\Exception\TrialCountOverException;
use Whoo\Config\Authentication as AuthConfig;

class ResetPassword extends Controller {
    protected function run() {
        $user = User::getByEmail($this->data['email']);
        if(!$user) {
            throw new NotFoundException;
        }
        if($this->config['DENY_IF_NOT_VERIFIED_TO_RESET_PW'] and !$user->getEmailVerified()) {
            throw new NotVerifiedEmailException;
        }
        $auth = AuthenticationCode::getByUserIdType($user->getId(), 'reset-password');
        if(!$auth) {
            throw new NotFoundAuthCodeException;
        }
        if($auth->getTrialCount()+1>=AuthConfig::TRIAL_MAX_COUNT_TO_RESET_PW) {
            throw new TrialCountOverException;
        }
        $dateTime = $auth->getDateTime();
        $timestamp = $dateTime->getTimestamp();
        if((time()-$timestamp)>AuthConfig::VALIDITY_TIME_TO_RESET_PW) {
            throw new TimeOutCodeException;
        }
        $code = $auth->getCode();
        if($code!==$this->data['code']) {
            AuthenticationCode::increaseTrialCount($auth);
            throw new InvalidCodeException;
        }
        User::setPassword($user, $this->data['newPassword']);
    }
}
