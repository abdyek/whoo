<?php

namespace Abdyek\Whoo\Controller;
use Abdyek\Whoo\Core\AbstractController;
use Abdyek\Whoo\Repository\AuthenticationCode;
use Abdyek\Whoo\Repository\User;
use Abdyek\Whoo\Exception\NotFoundException;
use Abdyek\Whoo\Exception\NotFoundAuthCodeException;
use Abdyek\Whoo\Exception\TimeOutCodeException;
use Abdyek\Whoo\Exception\TrialCountOverException;
use Abdyek\Whoo\Exception\InvalidCodeException;
use Abdyek\Whoo\Config\Authentication as AuthConfig;
use Abdyek\Whoo\Tool\JWT;

class SignIn2FA extends AbstractController
{
    public function run(): void
    {
        $content = $this->data->getContent();

        $user = User::getByEmail($content['email']);

        if(!$user) {
            throw new NotFoundException;
        }

        $auth = AuthenticationCode::getByUserIdType($user->getId(), AuthConfig::TYPE_2FA);
        if(!$auth) {
            throw new NotFoundAuthCodeException;
        }

        if($auth->getTrialCount()+1 >= $this->config->getTrialMaxCountToSignIn2fa()) {
            throw new TrialCountOverException;
        }

        $dateTime = $auth->getDateTime();
        $timestamp = $dateTime->getTimestamp();
        if(($this->dateTime->getTimestamp() - $timestamp) > $this->config->getValidityTimeToSignIn2fa()) {
            throw new TimeOutCodeException;
        }
        if($auth->getCode() !== $content['authCode']) {
            AuthenticationCode::increaseTrialCount($auth);
            throw new InvalidCodeException;
        }
        $jwt = JWT::generateToken($user->getId(), $user->getSignOutCount());

        AuthenticationCode::delete($auth);

        $this->response->setContent([
            'jwt' => $jwt,
            'user' => $user
        ]);
    }
}
