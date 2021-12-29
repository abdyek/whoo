<?php

namespace Abdyek\Whoo\Controller;
use Abdyek\Whoo\Core\AbstractController;
use Abdyek\Whoo\Config\Authentication as AuthConfig;
use Abdyek\Whoo\Model\User;
use Abdyek\Whoo\Model\AuthenticationCode;
use Abdyek\Whoo\Tool\Random;
use Abdyek\Whoo\Exception\NotFoundException;
use Abdyek\Whoo\Exception\NotVerifiedEmailException;

class SetAuthCodeToResetPassword extends AbstractController
{
    public function run(): void
    {
        $content = $this->data->getContent();

        $user = User::getByEmail($content['email']);
        if(!$user) {
            throw new NotFoundException;
        }

        if($this->config->getDenyIfNotVerifiedToResetPw() and !$user->getEmailVerified()) {
            $e = new NotVerifiedEmailException;
            $e->generateAuthCode($user);
            throw $e;
        }

        AuthenticationCode::deleteByUserIdType($user->getId(), AuthConfig::TYPE_RESET_PW);
        $authCode = Random::chars($this->config->getSizeOfCodeToResetPw());
        AuthenticationCode::create($user->getId(), AuthConfig::TYPE_RESET_PW, $authCode);

        $this->response->setContent([
            'authCode' => $authCode
        ]);
    }
}
