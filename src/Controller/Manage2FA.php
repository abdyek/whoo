<?php

namespace Abdyek\Whoo\Controller;
use Abdyek\Whoo\Core\AbstractController;
use Abdyek\Whoo\Model\AuthenticationCode;
use Abdyek\Whoo\Model\User;
use Abdyek\Whoo\Exception\NotFoundAuthCodeException;
use Abdyek\Whoo\Exception\TrialCountOverException;
use Abdyek\Whoo\Exception\TimeOutCodeException;
use Abdyek\Whoo\Exception\InvalidCodeException;
use Abdyek\Whoo\Config\Authentication as AuthConfig;

class Manage2FA extends AbstractController
{
    public function run(): void
    {
        $content = $this->data->getContent();
        $user = $this->authenticator->getUser();

        $auth = AuthenticationCode::getByUserIdType($user->getId(), AuthConfig::TYPE_MANAGE_2FA);
        if(!$auth) {
            throw new NotFoundAuthCodeException;
        }

        if($auth->getTrialCount()+1 >= $this->config->getTrialMaxCountToManage2fa()) {
            throw new TrialCountOverException;
        }

        if($this->dateTime->getTimestamp() - $auth->getDateTime()->getTimestamp() > $this->config->getValidityTimeToManage2fa()) {
            throw new TimeOutCodeException;
        }

        if($auth->getCode() !== $content['authCode']) {
            AuthenticationCode::increaseTrialCount($auth);
            throw new InvalidCodeException;
        }

        User::set2FA($user, $content['open']);
        AuthenticationCode::delete($auth);
    }
}
