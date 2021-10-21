<?php

namespace Abdyek\Whoo\Controller;
use Abdyek\Whoo\Core\Controller;
use Abdyek\Whoo\Model\User as UserModel;
use Abdyek\Whoo\Config\JWT as JWTConfig;
use Abdyek\Whoo\Exception\SignUpByEmailException;
use Abdyek\Whoo\Exception\NullUsernameException;
use Abdyek\Whoo\Tool\JWT;
use Abdyek\Whoo\Config\Whoo as Config;

class SignInByProvider extends Controller {
    public $registering = false;
    public $jwt = null;
    protected function run() {
        $user = UserModel::getByEmail($this->data['email']);
        if($user===null) {
            $user = UserModel::create($this->data);
            $this->registering = true;
        } else if(Config::$DENY_IF_SIGN_UP_BEFORE_BY_EMAIL and $user->getProvider()===null) {
            throw new SignUpByEmailException;
        }
        $user = UserModel::getByEmail($this->data['email']);
        if(Config::$USE_USERNAME and $user->getUsername()===null) {
            $e = new NullUsernameException;
            $e->generateTempToken($user);
            throw $e;
        }
        $this->jwt = JWT::generateToken($user->getId(), $user->getSignOutCount());
    }
}
