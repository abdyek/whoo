<?php

namespace Whoo\Controller;
use Whoo\Core\Controller;
use Whoo\Tool\TemporaryToken;
use Whoo\Exception\InvalidTemporaryTokenException;
use Whoo\Exception\NotNullUsernameException;
use Whoo\Exception\NotUniqueUsernameException;
use Whoo\Model\User;

class SetUsername extends Controller {
    protected function run() {
        $userId = TemporaryToken::getUserId($this->data['temporaryToken']);
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
