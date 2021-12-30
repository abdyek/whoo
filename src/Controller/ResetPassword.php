<?php

namespace Abdyek\Whoo\Controller;
use Abdyek\Whoo\Core\AbstractController;
use Abdyek\Whoo\Model\User;
use Abdyek\Whoo\Model\AuthenticationCode;
use Abdyek\Whoo\Exception\NotFoundException;
use Abdyek\Whoo\Exception\NotFoundAuthCodeException;
use Abdyek\Whoo\Exception\InvalidCodeException;
use Abdyek\Whoo\Exception\TimeOutCodeException;
use Abdyek\Whoo\Exception\NotVerifiedEmailException;
use Abdyek\Whoo\Exception\TrialCountOverException;
use Abdyek\Whoo\Config\Authentication as AuthConfig;

class ResetPassword extends AbstractController
{
    public function run(): void
    {
        $content = $this->data->getContent();

        $user = User::getByEmail($content['email']);
        if(!$user) {
            throw new NotFoundException;
        }

        if($this->config->getDenyIfNotVerifiedToResetPw() and !$user->getEmailVerified()) {
            throw new NotVerifiedEmailException;
        }

        $auth = AuthenticationCode::getByUserIdType($user->getId(), AuthConfig::TYPE_RESET_PW);
        if(!$auth) {
            throw new NotFoundAuthCodeException;
        }

        if($auth->getTrialCount()+1 >= $this->config->getTrialMaxCountToResetPw()) {
            throw new TrialCountOverException;
        }

        if($this->dateTime->getTimestamp() - $auth->getDateTime()->getTimestamp() > $this->config->getValidityTimeToResetPw()) {
            throw new TimeOutCodeException;
        }

        if($auth->getCode() !== $content['authCode']) {
            AuthenticationCode::increaseTrialCount($auth);
            throw new InvalidCodeException;
        }

        User::setPassword($user, $content['newPassword']);
    }
}
