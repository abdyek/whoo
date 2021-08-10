<?php

namespace Whoo\Controller;
use Whoo\Core\Controller;
use Whoo\Model\User;
use Whoo\Model\AuthenticationCode;
use Whoo\Tool\Random;
use Whoo\Exception\NotFoundException;
use Whoo\Config\Authentication as AuthConfig;

class SetAuthCodeForEmailVerification extends Controller {
    public $code;
    protected function run() {
        $user = User::getByEmail($this->data['email']);
        if($user===null) {
            throw new NotFoundException;
        }
        $this->code = Random::chars(AuthConfig::SIZE_OF_CODE_FOR_EMAIL_VER);
        AuthenticationCode::create($user->getId(), 'emailVerification', $this->code);
    }
}
