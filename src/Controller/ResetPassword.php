<?php

namespace Whoo\Controller;
use Whoo\Core\Controller;
use Whoo\Model\User;
use Whoo\Model\AuthenticationCode;
use Whoo\Exception\NotFoundException;
use Whoo\Exception\NotFoundAuthCodeException;
use Whoo\Exception\InvalidCodeException;
use Whoo\Exception\NotVerifiedEmailException;

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
        $code = $auth->getCode();
        if($code!==$this->data['code']) {
            throw new InvalidCodeException;
        }
        User::setPassword($user, $this->data['newPassword']);
    }
}
