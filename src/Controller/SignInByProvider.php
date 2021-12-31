<?php

namespace Abdyek\Whoo\Controller;
use Abdyek\Whoo\Core\AbstractController;
use Abdyek\Whoo\Repository\User;
use Abdyek\Whoo\Config\JWT as JWTConfig;
use Abdyek\Whoo\Exception\SignUpByEmailException;
use Abdyek\Whoo\Exception\NullUsernameException;
use Abdyek\Whoo\Tool\JWT;

class SignInByProvider extends AbstractController
{
    public function run(): void
    {
        $firstSignIn = false;
        $content = $this->data->getContent();

        $user = User::getByEmail($content['email']);
        if(!$user) {
            $user = User::create($content);
            $firstSignIn = true;
        } else if($this->config->getDenyIfSignUpBeforeByEmail() and !$user->getProvider()) {
            throw new SignUpByEmailException;
        }

        $user = User::getByEmail($content['email']);
        if($this->config->getUseUsername() and !$user->getUsername()) {
            $e = new NullUsernameException;
            $e->generateTempToken($user);
            throw $e;
        }

        $this->response->setContent([
            'jwt' => JWT::generateToken($user->getId(), $user->getSignOutCount()),
            'firstSignIn' => $firstSignIn,
        ]);
    }
}
