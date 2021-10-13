<?php

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
use Abdyek\Whoo\Config\Whoo as Config;
use Abdyek\Whoo\Config\Propel as PropelConfig;

/**
 * @covers ResetPassword::
 */

class ResetPasswordTest extends TestCase {
    use DefaultConfig;
    use Reset;
    const NEW_PASSWORD = 'n e w PW';
    public static function setUpBeforeClass(): void {
        PropelConfig::$CONFIG_FILE = 'propel/config.php';
    }
    public function setUp(): void {
        self::setDefaultConfig();
        self::reset();
    }
    public function testRun() {
        $data = $this->getData();
        Config::$USE_USERNAME = false;
        Config::$DENY_IF_NOT_VERIFIED_TO_SIGN_IN = false;
        Config::$DENY_IF_NOT_VERIFIED_TO_RESET_PW = false;
        new SignUp($data);
        $setAuth = new SetAuthCodeToResetPassword([
            'email'=>$data['email']
        ]);
        new ResetPassword([
            'email'=>$data['email'],
            'newPassword'=>self::NEW_PASSWORD,
            'code'=>$setAuth->code
        ]);
        $data['password'] = self::NEW_PASSWORD;
        $signIn = new SignIn($data);
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
        Config::$USE_USERNAME = false;
        Config::$DENY_IF_NOT_VERIFIED_TO_SIGN_IN = false;
        Config::$DENY_IF_NOT_VERIFIED_TO_RESET_PW = false;
        new SignUp($data);
        new SetAuthCodeToResetPassword([
            'email'=>$data['email']
        ]);
        new ResetPassword([
            'email'=>$data['email'],
            'newPassword'=>self::NEW_PASSWORD,
            'code'=>'w-rg-c-o-e'
        ]);
    }
    public function testRunNotFoundAuthCodeException() {
        $this->expectException(NotFoundAuthCodeException::class);
        $data = $this->getData();
        Config::$USE_USERNAME = false;
        Config::$DENY_IF_NOT_VERIFIED_TO_SIGN_IN = false;
        Config::$DENY_IF_NOT_VERIFIED_TO_RESET_PW = false;
        new SignUp($data);
        new ResetPassword([
            'email'=>$data['email'],
            'newPassword'=>self::NEW_PASSWORD,
            'code'=>'codee'
        ]);
    }
    public function testRunDenyIfNotVerifiedToResetPWTrue() {
        $this->expectException(NotVerifiedEmailException::class);
        $data = $this->getData();
        Config::$USE_USERNAME = false;
        Config::$DENY_IF_NOT_VERIFIED_TO_SIGN_IN = false;
        Config::$DENY_IF_NOT_VERIFIED_TO_RESET_PW = false;
        new SignUp($data);
        $setAuth = new SetAuthCodeToResetPassword([
            'email'=>$data['email']
        ]);
        Config::$DENY_IF_NOT_VERIFIED_TO_RESET_PW = true;
        new ResetPassword([
            'email'=>$data['email'],
            'newPassword'=>self::NEW_PASSWORD,
            'code'=>$setAuth->code
        ]);
    }
    public function testRunTrialCountOverException() {
        $this->expectException(TrialCountOverException::class);
        $data = $this->getData();
        Config::$USE_USERNAME = false;
        Config::$DENY_IF_NOT_VERIFIED_TO_SIGN_IN = false;
        Config::$DENY_IF_NOT_VERIFIED_TO_RESET_PW = false;
        new SignUp($data);
        $setAuth = new SetAuthCodeToResetPassword([
            'email'=>$data['email']
        ]);
        for($i=0;$i<AuthConfig::$TRIAL_MAX_COUNT_TO_RESET_PW;$i++) {
            try {
                new ResetPassword([
                    'email'=>$data['email'],
                    'newPassword'=>'this_is_new_ps',
                    'code'=>'wrong-code'
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
            'email'=>'thisIsEmail@email.com',
            'password'=>'p a s s w o r d',
        ];
    }
}
