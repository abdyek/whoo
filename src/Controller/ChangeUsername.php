<?php

namespace Abdyek\Whoo\Controller;
use Abdyek\Whoo\Core\AbstractController;
use Abdyek\Whoo\Model\User;
use Abdyek\Whoo\Exception\NotUniqueUsernameException;

class ChangeUsername extends AbstractController
{
    public function run(): void
    {
        $content = $this->data->getContent();
        if(!User::isUniqueUsername($content['newUsername'])) {
            throw new NotUniqueUsernameException;
        }
        User::setUsername($this->authenticator->getUser(), $content['newUsername']);
    }
}
