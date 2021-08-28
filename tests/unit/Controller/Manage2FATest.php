<?php

require 'propel/config.php';
use PHPUnit\Framework\TestCase;
use Whoo\Controller\Manage2FA;
use Whoo\Controller\SignUp;
use Whoo\Controller\SignIn;
use Whoo\Controller\SetAuthCodeToManage2FA;
use Whoo\Exception\NotFoundAuthCodeException;
use Whoo\Exception\InvalidCodeException;;
use Whoo\Exception\TrialCountOverException;;
use Whoo\Config\Authentication as AuthConfig;

/**
 * @covers Manage2FA::
 */

class Manage2FATest extends TestCase {
    use Reset;
    use ChangeConfig;
    public function setUp(): void {
        self::reset();
    }
    public function testRun() {
        $data = $this->getData();
        $config = $this->changeConfig([
            'USE_USERNAME'=>false,
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>false,
            'DEFAULT_2FA'=>false
        ]);
        new SignUp($data, $config);
        $signIn = new SignIn($data, $config);
        $auth = new SetAuthCodeToManage2FA([
            'jwt'=>$signIn->jwt
        ], $config);
        new Manage2FA([
            'jwt'=>$signIn->jwt,
            'code'=>$auth->code,
            'open'=>true
        ], $config);
        $this->assertTrue($signIn->user->getTwoFactorAuthentication());
    }
    public function testRunNotFoundAuthCodeException() {
        $this->expectException(NotFoundAuthCodeException::class);
        $data = $this->getData();
        $config = $this->changeConfig([
            'USE_USERNAME'=>false,
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>false,
            'DEFAULT_2FA'=>false
        ]);
        new SignUp($data, $config);
        $signIn = new SignIn($data, $config);
        new Manage2FA([
            'jwt'=>$signIn->jwt,
            'code'=>'thisIsCode',
            'open'=>true
        ], $config);
    }
    public function testRunTrialCountOverException() {
        $this->expectException(TrialCountOverException::class);
        $data = $this->getData();
        $config = $this->changeConfig([
            'USE_USERNAME'=>false,
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>false,
            'DEFAULT_2FA'=>false
        ]);
        new SignUp($data, $config);
        $signIn = new SignIn($data, $config);
        new SetAuthCodeToManage2FA([
            'jwt'=>$signIn->jwt
        ], $config);
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
