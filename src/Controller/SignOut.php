<?php

namespace Whoo\Controller;
use Whoo\Core\Controller;
use Whoo\Model\User;

class SignOut extends Controller {
    protected function run() {
        if($this->config['REAL_STATELESS']===false) {
            $user = User::getById($this->userId);
            User::increaseSignOutCount($user);
        }
    }
}
