<?php

require 'propel/config.php';
use PHPUnit\Framework\TestCase;
use Whoo\Controller\FetchInfo;
use Whoo\Controller\SignUp;
use Whoo\Controller\SignIn;
use Whoo\Controller\SetUsername;
use Whoo\Model\User;
use Whoo\Exception\InvalidTokenException;

/**
 * @covers FetchInfo::
 */

class FetchInfoTest extends TestCase {
    use Reset;
    public function setUp(): void {
        self::reset();
    }
    public function testRun() {
        $data = $this->getData();
        unset($data['username']);
        $username  = $this->getData()['username'];
        $signUp = new SignUp($data);
        new SetUsername([
            'temporaryToken'=>$signUp->temporaryToken,
            'username'=>$username
        ]);
        $signIn = new SignIn($data);
        $fetchInfo = new FetchInfo([
            'jwt'=>$signIn->jwt
        ]);
        $user = User::getByUsername($username);
        $this->assertSame($user, $fetchInfo->user);
    }
    public function testRunInvalidTokenException() {
        $this->expectException(InvalidTokenException::class);
        new FetchInfo([
            'jwt'=>'wrong_jwt'
        ]);
    }
    private function getData() {
        return [
            'email'=>'test@test.com',
            'password'=>'this_is_password',
            'username'=>'userName'
        ];
    }
}

