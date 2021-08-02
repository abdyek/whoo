<?php

namespace Whoo\Controller;
use Whoo\Core\Controller;
use Whoo\Model\Member as MemberModel;
use Whoo\Exception\NotUniqueEmailException;

class SignUp extends Controller {
    protected function run() {
        if(!MemberModel::isUniqueEmail($this->data['email'])) {
            throw new NotUniqueEmailException;
        }
        MemberModel::create($this->data);
        $this->setSuccess();
    }
}
