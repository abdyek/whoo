<?php

namespace Whoo\Controller;
use Whoo\Core\Controller;
use Whoo\Model\User;

class FetchInfo extends Controller {
    public $user;
    protected function run() {
        $this->user = User::getById($this->userId);
    }
}
