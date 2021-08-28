<?php

namespace Whoo\Controller;
use Whoo\Core\Controller;
use Whoo\Model\AuthenticationCode;
use Whoo\Model\User;
use Whoo\Exception\NotFoundAuthCodeException;
use Whoo\Exception\TrialCountOverException;
use Whoo\Exception\TimeOutCodeException;
use Whoo\Exception\InvalidCodeException;
use Whoo\Config\Authentication as AuthConfig;

class Manage2FA extends Controller {
    protected function run () {
        $auth = AuthenticationCode::getByUserIdType($this->user->getId(), '2FA');
        if(!$auth) {
            throw new NotFoundAuthCodeException;
        }
        if($auth->getTrialCount()+1>=AuthConfig::TRIAL_MAX_COUNT_TO_MANAGE_2FA) {
            throw new TrialCountOverException;
        }
        $dataTime = $auth->getDateTime();
        $timestamp = $dataTime->getTimestamp();
        if((time()-$timestamp)>AuthConfig::VALIDITY_TIME_TO_MANAGE_2FA) {
            throw new TimeOutCodeException;
        }
        if($auth->getCode()!==$this->data['code']) {
            AuthenticationCode::increaseTrialCount($auth);
            throw new InvalidCodeException;
        }
        User::set2FA($this->user, $this->data['open']);
        AuthenticationCode::delete($auth);
    }
}
