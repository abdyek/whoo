<?php

namespace Abdyek\Whoo\Controller;
use Abdyek\Whoo\Core\Controller;
use Abdyek\Whoo\Model\User;

class SignOut extends Controller {
    protected function run() {
        User::increaseSignOutCount($this->user);
    }
}
