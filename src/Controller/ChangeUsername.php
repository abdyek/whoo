<?php

namespace Abdyek\Whoo\Controller;
use Abdyek\Whoo\Core\Controller;
use Abdyek\Whoo\Model\User;
use Abdyek\Whoo\Exception\NotUniqueUsernameException;

class ChangeUsername extends Controller {
    protected function run() {
        if(!User::isUniqueUsername($this->data['newUsername'])) {
            throw new NotUniqueUsernameException;
        }
        User::setUsername($this->user, $this->data['newUsername']);
    }
}
