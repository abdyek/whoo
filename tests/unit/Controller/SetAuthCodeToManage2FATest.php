<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Controller\SetAuthCodeToManage2FA;
use Abdyek\Whoo\Controller\SignUp;
use Abdyek\Whoo\Controller\SignIn;
use Abdyek\Whoo\Model\AuthenticationCode;
use Abdyek\Whoo\Config\Authentication as AuthConfig;
use Abdyek\Whoo\Exception\IncorrectPasswordException;
use Abdyek\Whoo\Config\Whoo as Config;
use Abdyek\Whoo\Config\Propel as PropelConfig;

/**
 * @covers SetAuthCodeToManage2FA::
 */

class SetAuthCodeToManage2FATest extends TestCase {
    use Reset;
    public static function setUpBeforeClass(): void {
        PropelConfig::$CONFIG_FILE = 'propel/config.php';
    }
    public function setUp(): void {
        self::reset();
    }
    public function testRun() {
        $data = $this->getData();
        Config::$USE_USERNAME = false;
        Config::$DENY_IF_NOT_VERIFIED_TO_SIGN_IN = false;
        Config::$DEFAULT_2FA = false;
        new SignUp($data);
        $signIn = new SignIn($data);
        new SetAuthCodeToManage2FA([
            'jwt'=>$signIn->jwt,
            'password'=>$data['password']
        ]);
        $authCode = AuthenticationCode::getByUserIdType($signIn->user->getId(), AuthConfig::TYPE_MANAGE_2FA);
        $this->assertNotNull($authCode->getCode());
        $this->assertEquals(AuthConfig::SIZE_OF_CODE_TO_MANAGE_2FA, strlen($authCode->getCode()));
    }
    public function testRunIncorrectPasswordException() {
        $this->expectException(IncorrectPasswordException::class);
        $data = $this->getData();
        Config::$USE_USERNAME = false;
        Config::$DENY_IF_NOT_VERIFIED_TO_SIGN_IN = false;
        Config::$DEFAULT_2FA = false;
        new SignUp($data);
        $signIn = new SignIn($data);
        new SetAuthCodeToManage2FA([
            'jwt'=>$signIn->jwt,
            'password'=>'wrong-password'
        ]);
    }
    private function getData() {
        return [
            'email'=>'this_is_email@email.com',
            'password'=>'this-is-password'
        ];
    }
}
