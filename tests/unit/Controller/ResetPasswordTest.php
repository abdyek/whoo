<?php

require 'propel/config.php';
use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Controller\ResetPassword;
use Abdyek\Whoo\Controller\SignUp;
use Abdyek\Whoo\Controller\SignIn;
use Abdyek\Whoo\Controller\SetAuthCodeToResetPassword;
use Abdyek\Whoo\Exception\InvalidCodeException;
use Abdyek\Whoo\Exception\NotFoundException;
use Abdyek\Whoo\Exception\NotFoundAuthCodeException;
use Abdyek\Whoo\Exception\NotVerifiedEmailException;
use Abdyek\Whoo\Exception\TrialCountOverException;
use Abdyek\Whoo\Config\Authentication as AuthConfig;

/**
 * @covers ResetPassword::
 */

class ResetPasswordTest extends TestCase {
    use Reset;
    use ChangeConfig;
    const NEW_PASSWORD = 'n e w PW';
    public function setUp(): void {
        self::reset();
    }
    public function testRun() {
        $data = $this->getData();
        $config = $this->changeConfig([
            'USE_USERNAME'=>false,
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>false,
            'DENY_IF_NOT_VERIFIED_TO_RESET_PW'=>false
        ]);
        new SignUp($data, $config);
        $setAuth = new SetAuthCodeToResetPassword([
            'email'=>$data['email']
        ], $config);
        new ResetPassword([
            'email'=>$data['email'],
            'newPassword'=>self::NEW_PASSWORD,
            'code'=>$setAuth->code
        ], $config);
        $data['password'] = self::NEW_PASSWORD;
        $signIn = new SignIn($data, $config);
        $this->assertNotNull($signIn->jwt);
    }
    public function testRunNotFoundException() {
        $this->expectException(NotFoundException::class);
        new ResetPassword([
            'email'=>'none@none.com',
            'newPassword'=>self::NEW_PASSWORD,
            'code'=>'code'
        ]);
    }
    public function testRunInvalidCodeException() {
        $this->expectException(InvalidCodeException::class);
        $data = $this->getData();
        $config = $this->changeConfig([
            'USE_USERNAME'=>false,
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>false,
            'DENY_IF_NOT_VERIFIED_TO_RESET_PW'=>false
        ]);
        new SignUp($data, $config);
        new SetAuthCodeToResetPassword([
            'email'=>$data['email']
        ], $config);
        new ResetPassword([
            'email'=>$data['email'],
            'newPassword'=>self::NEW_PASSWORD,
            'code'=>'w-rg-c-o-e'
        ], $config);
    }
    public function testRunNotFoundAuthCodeException() {
        $this->expectException(NotFoundAuthCodeException::class);
        $data = $this->getData();
        $config = $this->changeConfig([
            'USE_USERNAME'=>false,
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>false,
            'DENY_IF_NOT_VERIFIED_TO_RESET_PW'=>false
        ]);
        new SignUp($data, $config);
        new ResetPassword([
            'email'=>$data['email'],
            'newPassword'=>self::NEW_PASSWORD,
            'code'=>'codee'
        ], $config);
    }
    public function testRunDenyIfNotVerifiedToResetPWTrue() {
        $this->expectException(NotVerifiedEmailException::class);
        $data = $this->getData();
        $config = $this->changeConfig([
            'USE_USERNAME'=>false,
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>false,
            'DENY_IF_NOT_VERIFIED_TO_RESET_PW'=>false
        ]);
        new SignUp($data, $config);
        $setAuth = new SetAuthCodeToResetPassword([
            'email'=>$data['email']
        ], $config);
        $config['DENY_IF_NOT_VERIFIED_TO_RESET_PW'] = true;
        new ResetPassword([
            'email'=>$data['email'],
            'newPassword'=>self::NEW_PASSWORD,
            'code'=>$setAuth->code
        ], $config);
    }
    public function testRunTrialCountOverException() {
        $this->expectException(TrialCountOverException::class);
        $data = $this->getData();
        $config = $this->changeConfig([
            'USE_USERNAME'=>false,
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>false,
            'DENY_IF_NOT_VERIFIED_TO_RESET_PW'=>false
        ]);
        new SignUp($data, $config);
        $setAuth = new SetAuthCodeToResetPassword([
            'email'=>$data['email']
        ], $config);
        for($i=0;$i<AuthConfig::TRIAL_MAX_COUNT_TO_RESET_PW;$i++) {
            try {
                new ResetPassword([
                    'email'=>$data['email'],
                    'newPassword'=>'this_is_new_ps',
                    'code'=>'wrong-code'
                ], $config);
            } catch(InvalidCodeException $e) { }
        }
    }
    /*
    public function testRunTimeOutCodeException() {
        // I will fill it after
    }*/
    private function getData() {
        return [
            'email'=>'thisIsEmail@email.com',
            'password'=>'p a s s w o r d',
        ];
    }
}
