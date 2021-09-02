<?php

namespace Abdyek\Whoo\Controller;
use Abdyek\Whoo\Core\Controller;
use Abdyek\Whoo\Config\Authentication as AuthConfig;
use Abdyek\Whoo\Model\User;
use Abdyek\Whoo\Model\AuthenticationCode;
use Abdyek\Whoo\Tool\Random;
use Abdyek\Whoo\Exception\NotFoundException;
use Abdyek\Whoo\Exception\NotVerifiedEmailException;

class SetAuthCodeToResetPassword extends Controller {
    public $code;
    protected function run() {
        $user = User::getByEmail($this->data['email']);
        if($user===null) {
            throw new NotFoundException;
        }
        if($this->config['DENY_IF_NOT_VERIFIED_TO_RESET_PW'] and $user->getEmailVerified()===false) {
            throw new NotVerifiedEmailException;
        }
        AuthenticationCode::deleteByUserIdType($user->getId(), AuthConfig::TYPE_RESET_PW);
        $this->code = Random::chars(AuthConfig::SIZE_OF_CODE_TO_RESET_PW);
        AuthenticationCode::create($user->getId(), AuthConfig::TYPE_RESET_PW, $this->code);
    }
}
