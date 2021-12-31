<?php

namespace Abdyek\Whoo\Controller;
use Abdyek\Whoo\Core\AbstractController;
use Abdyek\Whoo\Tool\TemporaryToken;
use Abdyek\Whoo\Exception\InvalidTemporaryTokenException;
use Abdyek\Whoo\Exception\NotNullUsernameException;
use Abdyek\Whoo\Exception\NotUniqueUsernameException;
use Abdyek\Whoo\Repository\User;

class SetUsername extends AbstractController
{
    public function run(): void
    {
        $content = $this->data->getContent();
        $userId = TemporaryToken::getUserId($content['tempToken']);

        if(!$userId) {
            throw new InvalidTemporaryTokenException;
        }

        $user = User::getById($userId);
        if($user->getUsername()) {
            throw new NotNullUsernameException;
        }

        $available = User::getByUsername($content['username']);
        if($available) {
            throw new NotUniqueUsernameException;
        }

        User::setUsername($user, $content['username']);
    }
}
