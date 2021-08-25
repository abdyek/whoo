<?php

namespace Whoo\Controller;
use Whoo\Core\Controller;
use Whoo\Model\User;
use Whoo\Controller\SignOut;
use Whoo\Exception\IncorrectPasswordException;
use Whoo\Exception\NotUniqueEmailException;

class ChangeEmail extends Controller {
    protected function run() {
        $user = User::getById($this->userId);
        if(!User::checkPassword($user, $this->data['password'])) {
            throw new IncorrectPasswordException;
        }
        if(!User::isUniqueEmail($this->data['newEmail'])) {
            throw new NotUniqueEmailException;
        }
        User::setEmail($user, $this->data['newEmail']);
        User::setEmailVerified($user, false);
        new SignOut(['jwt'=>$this->data['jwt']]);
    }
}
