<?php

namespace Abdyek\Whoo\Exception;

class TwoFactorAuthEnabledException extends \Exception {
    public $authCode = null;
    public function setAuthenticationCode($authCode) {
        $this->authCode = $authCode;
    }
}
