<?php

namespace Abdyek\Whoo\Controller;
use Abdyek\Whoo\Core\Controller;
use Abdyek\Whoo\Model\User;
use Abdyek\Whoo\Model\AuthenticationCode;
use Abdyek\Whoo\Tool\Random;
use Abdyek\Whoo\Exception\NotFoundException;
use Abdyek\Whoo\Config\Authentication as AuthConfig;

class SetAuthCodeForEmailVerification extends Controller {
    public $code;
    protected function run() {
        $user = User::getByEmail($this->data['email']);
        if($user===null) {
            throw new NotFoundException;
        }
        AuthenticationCode::deleteByUserIdType($user->getId(), AuthConfig::TYPE_EMAIL_VERIFICATION);
        $this->code = Random::chars(AuthConfig::$SIZE_OF_CODE_TO_VERIFY_EMAIL);
        AuthenticationCode::create($user->getId(), AuthConfig::TYPE_EMAIL_VERIFICATION, $this->code);
    }
}
