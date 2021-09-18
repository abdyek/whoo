<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Controller\Manage2FA;
use Abdyek\Whoo\Controller\SignUp;
use Abdyek\Whoo\Controller\SignIn;
use Abdyek\Whoo\Controller\SetAuthCodeToManage2FA;
use Abdyek\Whoo\Exception\NotFoundAuthCodeException;
use Abdyek\Whoo\Exception\InvalidCodeException;;
use Abdyek\Whoo\Exception\TrialCountOverException;;
use Abdyek\Whoo\Config\Authentication as AuthConfig;
use Abdyek\Whoo\Config\Whoo as Config;
use Abdyek\Whoo\Config\Propel as PropelConfig;

/**
 * @covers Manage2FA::
 */

class Manage2FATest extends TestCase {
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
        $auth = new SetAuthCodeToManage2FA([
            'jwt'=>$signIn->jwt,
            'password'=>$data['password']
        ]);
        new Manage2FA([
            'jwt'=>$signIn->jwt,
            'code'=>$auth->code,
            'open'=>true
        ]);
        $this->assertTrue($signIn->user->getTwoFactorAuthentication());
    }
    public function testRunInvalidCodeException() {
        $this->expectException(InvalidCodeException::class);
        $data = $this->getData();
        Config::$USE_USERNAME = false;
        Config::$DENY_IF_NOT_VERIFIED_TO_SIGN_IN = false;
        Config::$DEFAULT_2FA = false;
        new SignUp($data);
        $signIn = new SignIn($data);
        $auth = new setAuthCodeToManage2FA([
            'jwt'=>$signIn->jwt,
            'password'=>$data['password']
        ]);
        new Manage2FA([
            'jwt'=>$signIn->jwt,
            'code'=>'wrong-code',
            'open'=>true
        ]);
    }
    public function testRunNotFoundAuthCodeException() {
        $this->expectException(NotFoundAuthCodeException::class);
        $data = $this->getData();
        Config::$USE_USERNAME = false;
        Config::$DENY_IF_NOT_VERIFIED_TO_SIGN_IN = false;
        Config::$DEFAULT_2FA = false;
        new SignUp($data);
        $signIn = new SignIn($data);
        new Manage2FA([
            'jwt'=>$signIn->jwt,
            'code'=>'thisIsCode',
            'open'=>true
        ]);
    }
    public function testRunTrialCountOverException() {
        $this->expectException(TrialCountOverException::class);
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
        for($i=0;$i<AuthConfig::TRIAL_MAX_COUNT_TO_MANAGE_2FA;$i++) {
            try {
                new Manage2FA([
                    'jwt'=>$signIn->jwt,
                    'code'=>'wrongCode-',
                    'open'=>true
                ]);
            } catch(InvalidCodeException $e) { }
        }
    }
    /*
    public function testRunTimeOutCodeException() {
        // I will fill it after
    }*/
    private function getData() {
        return [
            'email'=>'email@email.com',
            'password'=>'this_is_password'
        ];
    }
}
