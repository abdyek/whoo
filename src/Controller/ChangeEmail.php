<?php

namespace Abdyek\Whoo\Controller;
use Abdyek\Whoo\Core\AbstractController;
use Abdyek\Whoo\Core\Data;
use Abdyek\Whoo\Model\User;
use Abdyek\Whoo\Controller\SignOut;
use Abdyek\Whoo\Exception\IncorrectPasswordException;
use Abdyek\Whoo\Exception\NotUniqueEmailException;

class ChangeEmail extends AbstractController
{
    public function run(): void
    {
        $content = $this->data->getContent();

        $user = $this->authenticator->getUser();
        if(!User::checkPassword($user, $content['password'])) {
            throw new IncorrectPasswordException;
        }

        if(!User::isUniqueEmail($content['newEmail'])) {
            throw new NotUniqueEmailException;
        }

        User::setEmail($user, $content['newEmail']);
        User::setEmailVerified($user, false);

        (new SignOut(new Data(['jwt' => $content['jwt']]), $this->config))->triggerRun();
    }
}
