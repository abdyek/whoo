<?php

namespace Abdyek\Whoo\Controller;
use Abdyek\Whoo\Core\AbstractController;
use Abdyek\Whoo\Repository\User;
use Abdyek\Whoo\Repository\AuthenticationCode;
use Abdyek\Whoo\Exception\TimeOutCodeException;
use Abdyek\Whoo\Exception\InvalidCodeException;
use Abdyek\Whoo\Exception\TrialCountOverException;
use Abdyek\Whoo\Exception\NotFoundException;
use Abdyek\Whoo\Exception\NotFoundAuthCodeException;
use Abdyek\Whoo\Config\Authentication as AuthConfig;

class VerifyEmail extends AbstractController
{
    public function run(): void
    {
        $content = $this->data->getContent();

        $user = User::getByEmail($content['email']);
        if(!$user) {
            throw new NotFoundException;
        }

        $auth = AuthenticationCode::getByUserIdType($user->getId(), AuthConfig::TYPE_EMAIL_VERIFICATION);
        if(!$auth) {
            throw new NotFoundAuthCodeException;
        }
 
        if($auth->getTrialCount()+1 >= $this->config->getTrialMaxCountToVerifyEmail()) {
            throw new TrialCountOverException;
        }

        if($this->dateTime->getTimestamp() - $auth->getDateTime()->getTimestamp() > $this->config->getValidityTimeToVerifyEmail()) {
            throw new TimeOutCodeException;
        }

        if($auth->getCode() !== $content['authCode']) {
            AuthenticationCode::increaseTrialCount($auth);
            throw new InvalidCodeException;
        }

        User::setEmailVerified($user, true);
        AuthenticationCode::delete($auth);
    }
}
