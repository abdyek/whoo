<?php

namespace Whoo\Controller;
use Whoo\Core\Controller;
use Whoo\Model\Member as MemberModel;
use Whoo\Exception\NotUniqueEmailException;
use Whoo\Exception\NotUniqueUsernameException;

class SignUp extends Controller {
    protected function run() {
        if(!MemberModel::isUniqueEmail($this->data['email'])) {
            throw new NotUniqueEmailException;
        }
        if(!MemberModel::isUniqueUsername($this->data['username'])) {
            throw new NotUniqueUsernameException;
        }
        MemberModel::create($this->data);
        $this->setSuccess();
    }
}
