<?php

namespace Whoo\Controller;
use Whoo\Core\Controller;
use Whoo\Model\User;
use Whoo\Exception\IncorrectPasswordException;

class ChangePassword extends Controller {
    protected function run() {
        if(!User::checkPassword($this->user, $this->data['password'])) {
            throw new IncorrectPasswordException;
        }
        User::setPassword($this->user, $this->data['newPassword']);
    }
}
