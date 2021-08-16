<?php

require 'propel/config.php';
use PHPUnit\Framework\TestCase;
use Whoo\Controller\SignOut;
use Whoo\Controller\SignUp;
use Whoo\Controller\SignIn;
use Whoo\Controller\FetchInfo;
use Whoo\Exception\InvalidTokenException;

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
            'BLOCK_NOT_VERIFIED'=>false,
            'REAL_STATELESS'=>false
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
    public function testRunRealStatelessTrue() {
        $config = $this->changeConfig([
            'USE_USERNAME'=>false,
            'REAL_STATELESS'=>true,
            'BLOCK_NOT_VERIFIED'=>false
        ]);
        $data = self::getData();
        $signUp = new SignUp($data, $config);
        $signIn = new SignIn($data, $config);
        $jwt = $signIn->jwt;
        new SignOut([
            'jwt'=>$jwt
        ], $config);
        $fetchInfo = new FetchInfo([
            'jwt'=>$jwt
        ], $config);
        $this->assertNotNull($fetchInfo->user);
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
