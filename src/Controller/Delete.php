<?php

namespace Whoo\Controller;
use Whoo\Core\Controller;
use Whoo\Model\User;
use Whoo\Exception\IncorrectPasswordException;

class Delete extends Controller {
    protected function run() {
        if(!User::checkPassword($this->user, $this->data['password'])) {
            throw new IncorrectPasswordException;
        }
        User::deleteWithAll($this->user);
    }
}
