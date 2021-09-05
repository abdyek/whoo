<?php

namespace Abdyek\Whoo\Controller;
use Abdyek\Whoo\Core\Controller;
use Abdyek\Whoo\Model\User;
use Abdyek\Whoo\Exception\NotUniqueEmailException;
use Abdyek\Whoo\Exception\UnmatchedPasswordsException;
use Abdyek\Whoo\Exception\NotUniqueUsernameException;
use Abdyek\Whoo\Tool\TemporaryToken;

class SignUp extends Controller {
    public $temporaryToken;
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
        $optionalUsername = ($this->config['USE_USERNAME'] and $this->isThereOptional('username'));
        if($optionalUsername and User::isUniqueUsername($this->data['username'])===false) {
            throw new NotUniqueUsernameException;
        }
        $data = array_merge($this->data, ['twoFactorAuthentication'=>$this->config['DEFAULT_2FA']]);
        $this->user = User::create($data);
        if($optionalUsername) {
            User::setUsername($this->user, $this->data['username']);
        } elseif($this->config['USE_USERNAME']) {
            $this->temporaryToken = TemporaryToken::generate($this->user->getId());
        }
    }
}
