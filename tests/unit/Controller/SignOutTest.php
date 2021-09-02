<?php

require 'propel/config.php';
use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Controller\SignOut;
use Abdyek\Whoo\Controller\SignUp;
use Abdyek\Whoo\Controller\SignIn;
use Abdyek\Whoo\Controller\FetchInfo;
use Abdyek\Whoo\Exception\InvalidTokenException;

/**
 * @covers SignOut::
 */

class SignOutTest extends TestCase {
    use Reset;
    use ChangeConfig;
    public function setUp(): void {
        self::reset();
    }
    public function testRunRealStatelessFalse() {
        $this->expectException(InvalidTokenException::class);
        $config = $this->changeConfig([
            'USE_USERNAME'=>false,
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>false
        ]);
        $data = self::getData();
        $signUp = new SignUp($data, $config);
        $signIn = new SignIn($data, $config);
        $jwt = $signIn->jwt;
        new SignOut([
            'jwt'=>$jwt
        ], $config);
        // Signed out. So FetchInfo throws exception
        new FetchInfo([
            'jwt'=>$jwt
        ], $config);
    }
    public function testTest() {
        $this->assertTrue(True);
    }
    private function getData() {
        return [
            'email'=>'example@example.com',
            'password'=>'this is password'
        ];
    }
}
