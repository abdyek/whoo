<?php

namespace Whoo\Controller;
use Whoo\Core\Controller;
use Whoo\Model\AuthenticationCode;
use Whoo\Config\Authentication as AuthConfig;
use Whoo\Tool\Random;

class SetAuthCodeToManage2FA extends Controller {
    public $code;
    protected function run() {
        $this->code = Random::number(AuthConfig::SIZE_OF_CODE_FOR_MANAGE_2FA);
        AuthenticationCode::create($this->user->getId(), '2FA', $this->code);
    }
}
