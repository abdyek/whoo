<?php

namespace Abdyek\Whoo\Controller;
use Abdyek\Whoo\Core\Controller;
use Abdyek\Whoo\Model\User;
use Abdyek\Whoo\Exception\IncorrectPasswordException;

class Delete extends Controller {
    protected function run() {
        if(!User::checkPassword($this->user, $this->data['password'])) {
            throw new IncorrectPasswordException;
        }
        User::deleteWithAll($this->user);
    }
}
