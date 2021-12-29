<?php

namespace Abdyek\Whoo\Controller;
use Abdyek\Whoo\Core\AbstractController;
use Abdyek\Whoo\Model\User;

class SignOut extends AbstractController
{
    public function run(): void
    {
        User::increaseSignOutCount($this->authenticator->getUser());
    }
}
