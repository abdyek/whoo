<?php

namespace Abdyek\Whoo\Controller;
use Abdyek\Whoo\Core\AbstractController;
use Abdyek\Whoo\Repository\User;
use Abdyek\Whoo\Repository\AuthenticationCode;
use Abdyek\Whoo\Config\Authentication as AuthConfig;
use Abdyek\Whoo\Exception\NotFoundException;
use Abdyek\Whoo\Exception\NotVerifiedEmailException;
use Abdyek\Whoo\Exception\IncorrectPasswordException;
use Abdyek\Whoo\Exception\TwoFactorAuthEnabledException;
use Abdyek\Whoo\Exception\UnmatchedPasswordsException;
use Abdyek\Whoo\Tool\JWT;
use Abdyek\Whoo\Tool\Random;

class SignInByUsername extends AbstractController
{
    public function run(): void
    {
        $content = $this->data->getContent();
        
        $user = User::getByUsername($content['username']);
        if(!$user) {
            throw new NotFoundException;
        }

        if(isset($content['passwordAgain']) and $content['password'] !== $content['passwordAgain']) {
            throw new UnmatchedPasswordsException;
        }

        if(!password_verify($content['password'], $user->getPasswordHash())) {
            throw new IncorrectPasswordException;
        }

        if($this->config->getDenyIfNotVerifiedToSignIn() and !$user->getEmailVerified()) {
            $e = new NotVerifiedEmailException;
            $e->generateAuthCode($user);
            throw $e;
        }

        if($user->getTwoFactorAuthentication()) {
            AuthenticationCode::deleteByUserIdType($user->getId(), AuthConfig::TYPE_2FA);
            $authCode = Random::number($this->config->getSizeOfCodeFor2fa());
            AuthenticationCode::create($user->getId(), AuthConfig::TYPE_2FA, $authCode);
            $e = new TwoFactorAuthEnabledException;
            $e->setAuthenticationCode($authCode);
            throw $e;
        }

        $jwt = $this->getAuthenticator()->getJWTObject()->generateToken($user->getId(), $user->getSignOutCount());
        $this->response->setContent([
            'jwt' => $jwt,
            'user' => $user
        ]);
    }
}
