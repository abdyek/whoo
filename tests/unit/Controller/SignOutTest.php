<?php

require 'propel/config.php';
use PHPUnit\Framework\TestCase;
use Whoo\Controller\SignOut;
use Whoo\Controller\SignUp;
use Whoo\Controller\SignIn;
use Whoo\Controller\SetUsername;

/**
 * @covers SignOut::
 */

class SignOutTest extends TestCase {
    use Reset;
    use UserTool;
    public function setUp(): void {
        self::reset();
    }
    public function testRunRealStatelessFalse() {
        $user = $this->createExample();
        $emailPassword = self::getData();
        $username = self::getData()['username'];
        unset($emailPassword['username']);
        $signUp = new SignUp($emailPassword);
        new SetUsername([
            'temporaryToken' => $signUp->temporaryToken,
            'username'=>$username
        ]);
        $signIn = new SignIn($emailPassword);
        $jwt = $signIn->jwt;
        SignOut([
            'jwt'=>$jwt
        ]);
        // Here will be FetchInfo controller class to check sign out
    }
    private function getData() {
        return [
            'email'=>'example@example.com',
            'password'=>'this is password',
            'username'=>'userName123'
        ];
    }
}
