<?php

namespace Whoo\Controller;
use Whoo\Core\Controller;
use Whoo\Model\User;
use Whoo\Exception\NotUniqueUsernameException;

class ChangeUsername extends Controller {
    protected function run() {
        if(!User::isUniqueUsername($this->data['newUsername'])) {
            throw new NotUniqueUsernameException;
        }
        $user = User::getById($this->userId);
        User::setUsername($user, $this->data['newUsername']);
    }
}
