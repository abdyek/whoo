<?php

namespace Abdyek\Whoo\Controller;
use Abdyek\Whoo\Core\Controller;
use Abdyek\Whoo\Model\User;
use Abdyek\Whoo\Controller\SignOut;
use Abdyek\Whoo\Exception\IncorrectPasswordException;
use Abdyek\Whoo\Exception\NotUniqueEmailException;

class ChangeEmail extends Controller {
    protected function run() {
        if(!User::checkPassword($this->user, $this->data['password'])) {
            throw new IncorrectPasswordException;
        }
        if(!User::isUniqueEmail($this->data['newEmail'])) {
            throw new NotUniqueEmailException;
        }
        User::setEmail($this->user, $this->data['newEmail']);
        User::setEmailVerified($this->user, false);
        new SignOut(['jwt'=>$this->data['jwt']]);
    }
}
