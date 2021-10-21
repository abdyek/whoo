<?php

namespace Abdyek\Whoo\Exception;

use Abdyek\Whoo\Model\AuthenticationCode;
use Abdyek\Whoo\Config\Authentication as AuthConfig;
use Abdyek\Whoo\Tool\Random;

class NotVerifiedEmailException extends \Exception {
    public $authCode;
    public function generateAuthCode($user) {
        AuthenticationCode::deleteByUserIdType($user->getId(), AuthConfig::TYPE_EMAIL_VERIFICATION);
        $authCode = Random::chars(AuthConfig::$SIZE_OF_CODE_TO_VERIFY_EMAIL);
        AuthenticationCode::create($user->getId(), AuthConfig::TYPE_EMAIL_VERIFICATION, $authCode);
        $this->authCode = $authCode;
    }
}
