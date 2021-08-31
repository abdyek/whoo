<?php

namespace Whoo\Controller;
use Whoo\Core\Controller;
use Whoo\Model\User as UserModel;
use Whoo\Exception\NotUniqueEmailException;
use Whoo\Tool\TemporaryToken;

class SignUp extends Controller {
    public $temporaryToken;
    public $user = null;
    protected function run() {
        if(!UserModel::isUniqueEmail($this->data['email'])) {
            throw new NotUniqueEmailException;
        }
        $data = array_merge($this->data, ['twoFactorAuthentication'=>$this->config['DEFAULT_2FA']]);
        $this->user = UserModel::create($data);
        if($this->config['USE_USERNAME']) {
            $this->temporaryToken = TemporaryToken::generate($this->user->getId());
        }
    }
}
