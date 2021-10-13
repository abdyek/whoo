<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Controller\ChangePassword;
use Abdyek\Whoo\Controller\SignUp;
use Abdyek\Whoo\Controller\SignIn;
use Abdyek\Whoo\Exception\IncorrectPasswordException;
use Abdyek\Whoo\Config\Whoo as Config;
use Abdyek\Whoo\Config\Propel as PropelConfig;

/**
 * @covers ChangePassword::
 */

class ChangePasswordTest extends TestCase {
    const NEW_PASSWORD = 'nEw_pAsSwOrD';
    use DefaultConfig;
    use Reset;
    public static function setUpBeforeClass(): void {
        PropelConfig::$CONFIG_FILE = 'propel/config.php';
    }
    public function setUp(): void {
        self::setDefaultConfig();
        self::reset();
    }
    public function testRun() {
        $data = $this->getData();
        Config::$DENY_IF_NOT_VERIFIED_TO_SIGN_IN = false;
        Config::$USE_USERNAME = false;
        Config::$DEFAULT_2FA = false;
        new SignUp($data);
        $signIn = new SignIn($data);
        new ChangePassword([
            'jwt'=>$signIn->jwt,
            'password'=>$data['password'],
            'newPassword'=>self::NEW_PASSWORD
        ]);
        $data['password'] = self::NEW_PASSWORD;
        $signIn = new SignIn($data);
        $this->assertNotNull($signIn);
    }
    public function testRunIncorrectPasswordException() {
        $this->expectException(IncorrectPasswordException::class);
        $data = $this->getData();
        Config::$DENY_IF_NOT_VERIFIED_TO_SIGN_IN = false;
        Config::$USE_USERNAME = false;
        new SignUp($data);
        $signIn = new SignIn($data);
        new ChangePassword([
            'jwt'=>$signIn->jwt,
            'password'=>'wrongPassword :( ',
            'newPassword'=>'thisIsNewPassword'
        ]);
    }
    private function getData() {
        return [
            'email'=>'emailExample@email.com',
            'password'=>'this_is_password'
        ];
    }
}
        
