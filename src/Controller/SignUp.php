<?php

namespace Abdyek\Whoo\Controller;
use Abdyek\Whoo\Core\Controller;
use Abdyek\Whoo\Model\User;
use Abdyek\Whoo\Exception\NotUniqueEmailException;
use Abdyek\Whoo\Exception\UnmatchedPasswordsException;
use Abdyek\Whoo\Exception\NotUniqueUsernameException;
use Abdyek\Whoo\Tool\TemporaryToken;
use Abdyek\Whoo\Config\Whoo as Config;

class SignUp extends Controller {
    public $tempToken;
    public $user = null;
    protected function run() {
        if(!User::isUniqueEmail($this->data['email'])) {
            throw new NotUniqueEmailException;
        }
        if($this->isThereOptional('passwordAgain')) {
            if($this->data['password']!==$this->data['passwordAgain']) {
                throw new UnmatchedPasswordsException;
            }
        }
        $optionalUsername = (Config::$USE_USERNAME and $this->isThereOptional('username'));
        if($optionalUsername and User::isUniqueUsername($this->data['username'])===false) {
            throw new NotUniqueUsernameException;
        }
        $data = array_merge($this->data, ['twoFactorAuthentication'=>Config::$DEFAULT_2FA]);
        $this->user = User::create($data);
        if($optionalUsername) {
            User::setUsername($this->user, $this->data['username']);
        } elseif(Config::$USE_USERNAME) {
            $this->tempToken = TemporaryToken::generate($this->user->getId());
        }
    }
}
