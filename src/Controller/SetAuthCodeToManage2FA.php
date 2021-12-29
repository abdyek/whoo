<?php

namespace Abdyek\Whoo\Controller;
use Abdyek\Whoo\Core\AbstractController;
use Abdyek\Whoo\Model\AuthenticationCode;
use Abdyek\Whoo\Model\User;
use Abdyek\Whoo\Exception\IncorrectPasswordException;
use Abdyek\Whoo\Config\Authentication as AuthConfig;
use Abdyek\Whoo\Tool\Random;

class SetAuthCodeToManage2FA extends AbstractController
{
    public function run(): void
    {
        $content = $this->data->getContent();

        $user = $this->authenticator->getUser();
        if(!User::checkPassword($user, $content['password'])) {
            throw new IncorrectPasswordException;
        }

        AuthenticationCode::deleteByUserIdType($user->getId(), AuthConfig::TYPE_MANAGE_2FA);
        $authCode = Random::number(AuthConfig::$SIZE_OF_CODE_TO_MANAGE_2FA);
        AuthenticationCode::create($user->getId(), AuthConfig::TYPE_MANAGE_2FA, $authCode);

        $this->response->setContent([
            'authCode' => $authCode,
        ]);
    }
}
