<?php

namespace Abdyek\Whoo\Controller;
use Abdyek\Whoo\Core\Controller;
use Abdyek\Whoo\Model\User;
use Abdyek\Whoo\Model\AuthenticationCode;
use Abdyek\Whoo\Exception\NotFoundException;
use Abdyek\Whoo\Exception\NotFoundAuthCodeException;
use Abdyek\Whoo\Exception\InvalidCodeException;
use Abdyek\Whoo\Exception\NotVerifiedEmailException;
use Abdyek\Whoo\Exception\TrialCountOverException;
use Abdyek\Whoo\Config\Authentication as AuthConfig;
use Abdyek\Whoo\Config\Whoo as Config;

class ResetPassword extends Controller {
    protected function run() {
        $user = User::getByEmail($this->data['email']);
        if(!$user) {
            throw new NotFoundException;
        }
        if(Config::$DENY_IF_NOT_VERIFIED_TO_RESET_PW and !$user->getEmailVerified()) {
            throw new NotVerifiedEmailException;
        }
        $auth = AuthenticationCode::getByUserIdType($user->getId(), AuthConfig::TYPE_RESET_PW);
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
