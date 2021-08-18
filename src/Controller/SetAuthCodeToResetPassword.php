<?php

namespace Whoo\Controller;
use Whoo\Core\Controller;
use Whoo\Config\Authentication as AuthConfig;
use Whoo\Model\User;
use Whoo\Model\AuthenticationCode;
use Whoo\Tool\Random;
use Whoo\Exception\NotFoundException;
use Whoo\Exception\NotVerifiedEmailException;

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
        $this->code = Random::chars(AuthConfig::SIZE_OF_CODE_TO_RESET_PW);
        AuthenticationCode::create($user->getId(), 'resetPassword', $this->code);
    }
}
