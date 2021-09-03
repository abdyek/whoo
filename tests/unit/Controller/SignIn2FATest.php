<?php

require 'propel/config.php';
use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Controller\SignIn2FA;
use Abdyek\Whoo\Controller\SignUp;
use Abdyek\Whoo\Controller\SignIn;
use Abdyek\Whoo\Exception\NotFoundException;
use Abdyek\Whoo\Exception\NotFoundAuthCodeException;
use Abdyek\Whoo\Exception\TrialCountOverException;
use Abdyek\Whoo\Exception\InvalidCodeException;
use Abdyek\Whoo\Exception\TwoFactorAuthEnabledException;
use Abdyek\Whoo\Config\Authentication as AuthConfig;

/**
 * @covers SignIn2FA::
 */

class SignIn2FATest extends TestCase {
    use Reset;
    use ChangeConfig;
    public function setUp(): void {
        self::reset();
    }
    public function testRun() {
        $data = $this->getData();
        $config = $this->changeConfig([
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>false,
            'USE_USERNAME'=>false,
            'DEFAULT_2FA'=>true
        ]);
        new SignUp($data, $config);
        try {
            $signIn = new SignIn($data, $config);
        } catch(TwoFactorAuthEnabledException $e) {
            $signIn2FA = new SignIn2FA([
                'email'=>$data['email'],
                'authenticationCode'=>$e->authenticationCode
            ]);
        }
        $this->assertNotNull($signIn2FA->jwt);
    }
    public function testRunNotFoundException() {
        $this->expectException(NotFoundException::class);
        new SignIn2FA([
            'email'=>'notfound@notfound.com',
            'authenticationCode'=>'nothing'
        ]);
    }
    public function testRunNotFoundAuthCodeException() {
        $this->expectException(NotFoundAuthCodeException::class);
        $data = $this->getData();
        $config = $this->changeConfig([
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>false,
            'USE_USERNAME'=>false
        ]);
        new SignUp($data, $config);
        new SignIn2FA([
            'email'=>$data['email'],
            'authenticationCode'=>'nothing'
        ], $config);
    }
    public function testRunTrialCountOverException() {
        $this->expectException(TrialCountOverException::class);
        $config = $this->changeConfig([
            'USE_USERNAME'=>false,
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>false,
            'DEFAULT_2FA'=>true
        ]);
        $data = $this->getData();
        new SignUp($data, $config);
        try {
            $signIn = new SignIn($data, $config);
        } catch(TwoFactorAuthEnabledException $e) {}
        for($i=0;$i<AuthConfig::TRIAL_MAX_COUNT_TO_SIGN_IN_2FA;$i++) {
            try {
                new SignIn2FA([
                    'email'=>$data['email'],
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
            'email'=>'example@email.com',
            'password'=>'this_is_pw'
        ];
    }
}
