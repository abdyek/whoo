<?php

namespace Abdyek\Whoo\Controller;
use Abdyek\Whoo\Core\AbstractController;
use Abdyek\Whoo\Repository\User;
use Abdyek\Whoo\Exception\NotUniqueEmailException;
use Abdyek\Whoo\Exception\UnmatchedPasswordsException;
use Abdyek\Whoo\Exception\NotUniqueUsernameException;
use Abdyek\Whoo\Tool\TemporaryToken;

class SignUp extends AbstractController
{
    public function run():void
    {
        $content = $this->data->getContent();

        if(!User::isUniqueEmail($content['email'])) {
            throw new NotUniqueEmailException;
        }
        if(isset($content['passwordAgain']) and $content['password'] !== $content['passwordAgain']) {
            throw new UnmatchedPasswordsException;
        }
        if($this->config->getUseUsername() and isset($content['username']) and !User::isUniqueUsername($content['username'])) {
            throw new NotUniqueUsernameException;
        }

        $data = array_merge($content, ['twoFactorAuthentication'=>$this->config->getDefault2fa()]);
        $user = User::create($data);

        $tempToken = null;
        if(!($this->config->getUseUsername() and isset($content['username']))) {
            $tempToken = TemporaryToken::generate($user->getId(), $this->config->getSecretKey());
        } else {
            User::setUsername($user, $content['username']);
        }
        $this->response->setContent([
            'user' => $user,
            'tempToken' => $tempToken
        ]);

    }
}
