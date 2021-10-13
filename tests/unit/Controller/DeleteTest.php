<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Controller\Delete;
use Abdyek\Whoo\Controller\SignUp;
use Abdyek\Whoo\Controller\SignIn;
use Abdyek\Whoo\Exception\IncorrectPasswordException;
use Abdyek\Whoo\Config\Whoo as Config;
use Abdyek\Whoo\Config\Propel as PropelConfig;

/**
 * @covers Delete::
 */

class DeleteTest extends TestCase {
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
        Config::$USE_USERNAME = false;
        Config::$DENY_IF_NOT_VERIFIED_TO_SIGN_IN = false;
        Config::$DEFAULT_2FA = false;
        $data = $this->getData();
        new SignUp($data);
        $signIn = new SignIn($data);
        new Delete([
            'jwt'=>$signIn->jwt,
            'password'=>$data['password']
        ]);
        $this->assertTrue($signIn->user->isDeleted());
    }
    public function testRunIncorrectPasswordException() {
        $this->expectException(IncorrectPasswordException::class);
        Config::$USE_USERNAME = false;
        Config::$DENY_IF_NOT_VERIFIED_TO_SIGN_IN = false;
        Config::$DEFAULT_2FA = false;
        $data = $this->getData();
        new SignUp($data);
        $signIn = new SignIn($data);
        new Delete([
            'jwt'=>$signIn->jwt,
            'password'=>'wrong-password'
        ]);
    }
    private function getData() {
        return [
            'email'=>'example@example.com',
            'password'=>'this_is_password'
        ];
    }
}
