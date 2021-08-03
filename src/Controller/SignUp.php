<?php

namespace Whoo\Controller;
use Whoo\Core\Controller;
use Whoo\Model\Member as MemberModel;
use Whoo\Exception\NotUniqueEmailException;
use Whoo\Tool\TemporaryToken;

class SignUp extends Controller {
    protected function run() {
        if(!MemberModel::isUniqueEmail($this->data['email'])) {
            throw new NotUniqueEmailException;
        }
        $member = MemberModel::create($this->data);
        $this->temporaryToken = TemporaryToken::generate($member->getId());
        $this->setSuccess();
    }
}
