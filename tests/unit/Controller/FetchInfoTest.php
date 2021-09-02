<?php

require 'propel/config.php';
use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Controller\FetchInfo;
use Abdyek\Whoo\Controller\SignUp;
use Abdyek\Whoo\Controller\SignIn;
use Abdyek\Whoo\Controller\SetUsername;
use Abdyek\Whoo\Model\User;
use Abdyek\Whoo\Exception\InvalidTokenException;

/**
 * @covers FetchInfo::
 */

class FetchInfoTest extends TestCase {
    use Reset;
    use ChangeConfig;
    public function setUp(): void {
        self::reset();
    }
    public function testRun() {
        $config = $this->changeConfig([
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>false,
            'USE_USERNAME'=>true
        ]);
        $data = $this->getData();
        unset($data['username']);
        $username  = $this->getData()['username'];
        $signUp = new SignUp($data, $config);
        new SetUsername([
            'temporaryToken'=>$signUp->temporaryToken,
            'username'=>$username
        ]);
        $signIn = new SignIn($data, $config);
        $fetchInfo = new FetchInfo([
            'jwt'=>$signIn->jwt
        ], $config);
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

