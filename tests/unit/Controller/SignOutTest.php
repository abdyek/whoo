<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Controller\SignOut;
use Abdyek\Whoo\Controller\SignUp;
use Abdyek\Whoo\Controller\SignIn;
use Abdyek\Whoo\Controller\FetchInfo;
use Abdyek\Whoo\Exception\InvalidTokenException;
use Abdyek\Whoo\Config\Whoo as Config;
use Abdyek\Whoo\Config\Propel as PropelConfig;

/**
 * @covers SignOut::
 */

class SignOutTest extends TestCase {
    use Reset;
    public static function setUpBeforeClass(): void {
        PropelConfig::$CONFIG_FILE = 'propel/config.php';
    }
    public function setUp(): void {
        self::reset();
    }
    public function testRunRealStatelessFalse() {
        $this->expectException(InvalidTokenException::class);
        Config::$USE_USERNAME = false;
        Config::$DENY_IF_NOT_VERIFIED_TO_SIGN_IN = false;
        $data = self::getData();
        $signUp = new SignUp($data);
        $signIn = new SignIn($data);
        $jwt = $signIn->jwt;
        new SignOut([
            'jwt'=>$jwt
        ]);
        // Signed out. So FetchInfo throws exception
        new FetchInfo([
            'jwt'=>$jwt
        ]);
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
