<?php

namespace Whoo\Controller;
use Whoo\Core\Controller;
use Whoo\Model\User as UserModel;
use Whoo\Exception\NotUniqueEmailException;
use Whoo\Tool\TemporaryToken;

class SignUp extends Controller {
    protected function run() {
        if(!UserModel::isUniqueEmail($this->data['email'])) {
            throw new NotUniqueEmailException;
        }
        $user = UserModel::create($this->data);
        $this->temporaryToken = TemporaryToken::generate($user->getId());
    }
}
