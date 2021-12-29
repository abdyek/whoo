<?php

namespace Abdyek\Whoo\Controller;
use Abdyek\Whoo\Core\AbstractController;
use Abdyek\Whoo\Model\User;
use Abdyek\Whoo\Model\AuthenticationCode;
use Abdyek\Whoo\Tool\Random;
use Abdyek\Whoo\Exception\NotFoundException;
use Abdyek\Whoo\Config\Authentication as AuthConfig;

class SetAuthCodeForEmailVerification extends AbstractController
{
    public function run(): void
    {
        $content = $this->data->getContent();

        $user = User::getByEmail($content['email']);
        if(!$user) {
            throw new NotFoundException;
        }

        AuthenticationCode::deleteByUserIdType($user->getId(), AuthConfig::TYPE_EMAIL_VERIFICATION);
        $authCode = Random::chars(AuthConfig::$SIZE_OF_CODE_TO_VERIFY_EMAIL);
        AuthenticationCode::create($user->getId(), AuthConfig::TYPE_EMAIL_VERIFICATION, $authCode);

        $this->response->setContent([
            'authCode' => $authCode,
        ]);
    }
}
