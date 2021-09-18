<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Controller\FetchInfo;
use Abdyek\Whoo\Controller\SignUp;
use Abdyek\Whoo\Controller\SignIn;
use Abdyek\Whoo\Controller\SetUsername;
use Abdyek\Whoo\Model\User;
use Abdyek\Whoo\Exception\InvalidTokenException;
use Abdyek\Whoo\Config\Whoo as Config;
use Abdyek\Whoo\Config\Propel as PropelConfig;

/**
 * @covers FetchInfo::
 */

class FetchInfoTest extends TestCase {
    use Reset;
    public static function setUpBeforeClass(): void {
        PropelConfig::$CONFIG_FILE = 'propel/config.php';
    }
    public function setUp(): void {
        self::reset();
    }
    public function testRun() {
        Config::$DENY_IF_NOT_VERIFIED_TO_SIGN_IN = false;
        Config::$USE_USERNAME = true;
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

