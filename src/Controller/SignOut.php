<?php

namespace Whoo\Controller;
use Whoo\Core\Controller;
use Whoo\Model\User;

class SignOut extends Controller {
    protected function run() {
        User::increaseSignOutCount($this->user);
    }
}
