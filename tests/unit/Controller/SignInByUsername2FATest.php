<?php

use PHPUnit\Framework\TestCase;
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
use Abdyek\Whoo\Config\Whoo as Config;
use Abdyek\Whoo\Config\Propel as PropelConfig;

/**
 * @covers SignInByUsername2FA::
 */

class SignInByUsername2FATest extends TestCase {
    use Reset;
    private const USERNAME = 'this_is_username';
    public static function setUpBeforeClass(): void {
        PropelConfig::$CONFIG_FILE = 'propel/config.php';
    }
    public function setUp(): void {
        self::reset();
    }
    public function testRun() {
        $this->assertTrue(true);
        Config::$USE_USERNAME = true;
        Config::$DENY_IF_NOT_VERIFIED_TO_SIGN_IN = false;
        Config::$DEFAULT_2FA = true;
        $data = $this->getData();
        $signUp = new SignUp($data);
        new SetUsername([
            'temporaryToken'=>$signUp->temporaryToken,
            'username'=>self::USERNAME
        ]);
        try {
            $signIn = new SignInByUsername([
                'username'=>self::USERNAME,
                'password'=>$data['password']
            ]);
        } catch(TwoFactorAuthEnabledException $e) {
            $signIn2FA = new SignInByUsername2FA([
                'username'=>self::USERNAME,
                'authenticationCode'=>$e->authenticationCode
            ]);
        }
        $this->assertNotNull($signIn2FA->jwt);
    }
    public function testRunInvalidCodeException() {
        $this->expectException(InvalidCodeException::class);
        Config::$USE_USERNAME = true;
        Config::$DENY_IF_NOT_VERIFIED_TO_SIGN_IN = false;
        Config::$DEFAULT_2FA = true;
        $data = $this->getData();
        $signUp = new SignUp($data);
        new SetUsername([
            'temporaryToken'=>$signUp->temporaryToken,
            'username'=>self::USERNAME
        ]);
        try {
            $signIn = new SignInByUsername([
                'username'=>self::USERNAME,
                'password'=>$data['password']
            ]);
        } catch(TwoFactorAuthEnabledException $e) {}
        $signIn2FA = new SignInByUsername2FA([
            'username'=>self::USERNAME,
            'authenticationCode'=>'wrong-code'
        ]);
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
        Config::$USE_USERNAME = true;
        Config::$DENY_IF_NOT_VERIFIED_TO_SIGN_IN = false;
        Config::$DEFAULT_2FA = true;
        $data = $this->getData();
        $signUp = new SignUp($data);
        new SetUsername([
            'temporaryToken'=>$signUp->temporaryToken,
            'username'=>self::USERNAME
        ]);
        $signIn2FA = new SignInByUsername2FA([
            'username'=>self::USERNAME,
            'authenticationCode'=>'123123123'
        ]);
    }
    public function testRunTrialCountOverException() {
        $this->expectException(TrialCountOverException::class);
        Config::$USE_USERNAME = true;
        Config::$DENY_IF_NOT_VERIFIED_TO_SIGN_IN = false;
        Config::$DEFAULT_2FA = true;
        $data = $this->getData();
        $signUp = new SignUp($data);
        new SetUsername([
            'temporaryToken'=>$signUp->temporaryToken,
            'username'=>self::USERNAME
        ]);
        try {
            $signIn = new SignInByUsername([
                'username'=>self::USERNAME,
                'password'=>$data['password']
            ]);
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
