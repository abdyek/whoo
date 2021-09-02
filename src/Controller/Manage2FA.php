<?php

namespace Abdyek\Whoo\Controller;
use Abdyek\Whoo\Core\Controller;
use Abdyek\Whoo\Model\AuthenticationCode;
use Abdyek\Whoo\Model\User;
use Abdyek\Whoo\Exception\NotFoundAuthCodeException;
use Abdyek\Whoo\Exception\TrialCountOverException;
use Abdyek\Whoo\Exception\TimeOutCodeException;
use Abdyek\Whoo\Exception\InvalidCodeException;
use Abdyek\Whoo\Config\Authentication as AuthConfig;

class Manage2FA extends Controller {
    protected function run () {
        $auth = AuthenticationCode::getByUserIdType($this->user->getId(), AuthConfig::TYPE_MANAGE_2FA);
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
