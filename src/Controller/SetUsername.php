<?php

namespace Abdyek\Whoo\Controller;
use Abdyek\Whoo\Core\Controller;
use Abdyek\Whoo\Tool\TemporaryToken;
use Abdyek\Whoo\Exception\InvalidTemporaryTokenException;
use Abdyek\Whoo\Exception\NotNullUsernameException;
use Abdyek\Whoo\Exception\NotUniqueUsernameException;
use Abdyek\Whoo\Model\User;

class SetUsername extends Controller {
    protected function run() {
        $userId = TemporaryToken::getUserId($this->data['tempToken']);
        if($userId===null) {
            throw new InvalidTemporaryTokenException;
        }
        $user = User::getById($userId);
        if($user->getUsername()!==null) {
            throw new NotNullUsernameException;
        }
        $available = User::getByUsername($this->data['username']);
        if($available!==null) {
            throw new NotUniqueUsernameException;
        }
        User::setUsername($user, $this->data['username']);
    }
}
