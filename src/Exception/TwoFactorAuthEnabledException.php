<?php

namespace Whoo\Exception;

class TwoFactorAuthEnabledException extends \Exception {
    public $authenticationCode = null;
    public function setAuthenticationCode($authCode) {
        $this->authenticationCode = $authCode;
    }
}
