<?php

namespace Abdyek\Whoo\Controller;
use Abdyek\Whoo\Core\AbstractController;
use Abdyek\Whoo\Model\User;
use Abdyek\Whoo\Exception\IncorrectPasswordException;

class ChangePassword extends AbstractController
{
    public function run(): void
    {
        $content = $this->data->getContent();

        $user = $this->authenticator->getUser();
        if(!User::checkPassword($user, $content['password'])) {
            throw new IncorrectPasswordException;
        }

        User::setPassword($user, $content['newPassword']);
    }
}
