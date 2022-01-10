<?php

namespace Abdyek\Whoo\Controller;
use Abdyek\Whoo\Core\AbstractController;
use Abdyek\Whoo\Repository\User;
use Abdyek\Whoo\Repository\AuthenticationCode;
use Abdyek\Whoo\Exception\NotFoundException;
use Abdyek\Whoo\Exception\NotFoundAuthCodeException;
use Abdyek\Whoo\Exception\TimeOutCodeException;
use Abdyek\Whoo\Exception\TrialCountOverException;
use Abdyek\Whoo\Exception\InvalidCodeException;
use Abdyek\Whoo\Config\Authentication as AuthConfig;
use Abdyek\Whoo\Tool\JWT;

class SignInByUsername2FA extends AbstractController
{
    public function run(): void
    {
        $content = $this->data->getContent();

        $user = User::getByUsername($content['username']);
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

        if(($this->dateTime->getTimestamp() - $auth->getDateTime()->getTimestamp()) > $this->config->getValidityTimeToSignIn2fa()) {
            throw new TimeOutCodeException;
        }

        if($auth->getCode() !== $content['authCode']) {
            AuthenticationCode::increaseTrialCount($auth);
            throw new InvalidCodeException;
        }

        $jwt = $this->getAuthenticator()->getJWTObject()->generateToken($user->getId(), $user->getSignOutCount());

        AuthenticationCode::delete($auth);

        $this->response->setContent([
            'jwt' => $jwt,
            'user' => $user
        ]);
    }
}
