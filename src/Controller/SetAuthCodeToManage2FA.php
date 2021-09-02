<?php

namespace Abdyek\Whoo\Controller;
use Abdyek\Whoo\Core\Controller;
use Abdyek\Whoo\Model\AuthenticationCode;
use Abdyek\Whoo\Model\User;
use Abdyek\Whoo\Exception\IncorrectPasswordException;
use Abdyek\Whoo\Config\Authentication as AuthConfig;
use Abdyek\Whoo\Tool\Random;

class SetAuthCodeToManage2FA extends Controller {
    private const AUTH_TYPE = '2FA';
    public $code;
    protected function run() {
        if(!User::checkPassword($this->user, $this->data['password'])) {
            throw new IncorrectPasswordException;
        }
        AuthenticationCode::deleteByUserIdType($this->user->getId(), self::AUTH_TYPE);
        $this->code = Random::number(AuthConfig::SIZE_OF_CODE_TO_MANAGE_2FA);
        AuthenticationCode::create($this->user->getId(), self::AUTH_TYPE, $this->code);
    }
}
