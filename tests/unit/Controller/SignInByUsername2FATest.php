<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Tool\Config;
use Abdyek\Whoo\Controller\SignInByUsername2FA;
use Abdyek\Whoo\Controller\SignUp;
use Abdyek\Whoo\Controller\SetUsername;
use Abdyek\Whoo\Controller\SignInByUsername;
use Abdyek\Whoo\Exception\TwoFactorAuthEnabledException;
use Abdyek\Whoo\Exception\NotFoundException;
use Abdyek\Whoo\Exception\NotFoundAuthCodeException;
use Abdyek\Whoo\Exception\TrialCountOverException;
use Abdyek\Whoo\Exception\InvalidCodeException;
use Abdyek\Whoo\Config\Authentication as AuthConfig;

/**
 * @covers SignInByUsername2FA::
 */

class SignInByUsername2FATest extends TestCase {
    use Reset;
    use ChangeConfig;
    private const USERNAME = 'this_is_username';
    public static function setUpBeforeClass(): void {
        Config::setPropelConfigDir('propel/config.php');
        Config::load(); // for reset
    }
    public function setUp(): void {
        self::reset();
    }
    public function testRun() {
        $this->assertTrue(true);
        $config = $this->changeConfig([
            'USE_USERNAME'=>true,
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>false,
            'DEFAULT_2FA'=>true
        ]);
        $data = $this->getData();
        $signUp = new SignUp($data, $config);
        new SetUsername([
            'temporaryToken'=>$signUp->temporaryToken,
            'username'=>self::USERNAME
        ], $config);
        try {
            $signIn = new SignInByUsername([
                'username'=>self::USERNAME,
                'password'=>$data['password']
            ], $config);
        } catch(TwoFactorAuthEnabledException $e) {
            $signIn2FA = new SignInByUsername2FA([
                'username'=>self::USERNAME,
                'authenticationCode'=>$e->authenticationCode
            ], $config);
        }
        $this->assertNotNull($signIn2FA->jwt);
    }
    public function testRunInvalidCodeException() {
        $this->expectException(InvalidCodeException::class);
        $config = $this->changeConfig([
            'USE_USERNAME'=>true,
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>false,
            'DEFAULT_2FA'=>true
        ]);
        $data = $this->getData();
        $signUp = new SignUp($data, $config);
        new SetUsername([
            'temporaryToken'=>$signUp->temporaryToken,
            'username'=>self::USERNAME
        ]);
        try {
            $signIn = new SignInByUsername([
                'username'=>self::USERNAME,
                'password'=>$data['password']
            ], $config);
        } catch(TwoFactorAuthEnabledException $e) {}
        $signIn2FA = new SignInByUsername2FA([
            'username'=>self::USERNAME,
            'authenticationCode'=>'wrong-code'
        ], $config);
    }
    public function testRunNotFoundException() {
        $this->expectException(NotFoundException::class);
        new SignInByUsername2FA([
            'username'=>'nothing',
            'authenticationCode'=>'12345'
        ]);
    }
    public function testRunNotFoundAuthCodeException() {
        $this->expectException(NotFoundAuthCodeException::class);
        $config = $this->changeConfig([
            'USE_USERNAME'=>true,
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>false,
            'DEFAULT_2FA'=>true
        ]);
        $data = $this->getData();
        $signUp = new SignUp($data, $config);
        new SetUsername([
            'temporaryToken'=>$signUp->temporaryToken,
            'username'=>self::USERNAME
        ]);
        $signIn2FA = new SignInByUsername2FA([
            'username'=>self::USERNAME,
            'authenticationCode'=>'123123123'
        ], $config);
    }
    public function testRunTrialCountOverException() {
        $this->expectException(TrialCountOverException::class);
        $config = $this->changeConfig([
            'USE_USERNAME'=>true,
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>false,
            'DEFAULT_2FA'=>true
        ]);
        $data = $this->getData();
        $signUp = new SignUp($data, $config);
        new SetUsername([
            'temporaryToken'=>$signUp->temporaryToken,
            'username'=>self::USERNAME
        ]);
        try {
            $signIn = new SignInByUsername([
                'username'=>self::USERNAME,
                'password'=>$data['password']
            ], $config);
        } catch(TwoFactorAuthEnabledException $e) {}
        for($i=0;$i<AuthConfig::TRIAL_MAX_COUNT_TO_SIGN_IN_2FA;$i++) {
            try {
                new SignInByUsername2FA([
                    'username'=>self::USERNAME,
                    'authenticationCode'=>'123123123'
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
            'email'=>'example@example.com',
            'password'=>'this_is_password'
        ];
    }
}
