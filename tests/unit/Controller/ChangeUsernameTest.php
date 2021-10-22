<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Controller\ChangeUsername;
use Abdyek\Whoo\Controller\SignUp;
use Abdyek\Whoo\Controller\SignIn;
use Abdyek\Whoo\Controller\SetUsername;
use Abdyek\Whoo\Exception\NotUniqueUsernameException;
use Abdyek\Whoo\Config\Whoo as Config;
use Abdyek\Whoo\Config\Propel as PropelConfig;

/**
 * @covers ChangeUsername::
 */

class ChangeUsernameTest extends TestCase {
    use DefaultConfig;
    use Reset;
    const NEW_USERNAME = 'this_is_username';
    public static function setUpBeforeClass(): void {
        PropelConfig::$CONFIG_FILE = 'propel/config.php';
    }
    public function setUp(): void {
        self::setDefaultConfig();
        self::reset();
    }
    public function testRun() {
        Config::$USE_USERNAME = true;
        Config::$DENY_IF_NOT_VERIFIED_TO_SIGN_IN = false;
        $data = $this->getData();
        $signUp = new SignUp($data);
        new SetUsername([
            'tempToken'=>$signUp->tempToken,
            'username'=>'username'
        ]);
        $signIn = new SignIn($data);
        new ChangeUsername([
            'jwt'=>$signIn->jwt,
            'newUsername'=>self::NEW_USERNAME
        ]);
        $this->assertSame(self::NEW_USERNAME, $signIn->user->getUsername());
    }
    public function testRunNotUniqueUsernameException() {
        $this->expectException(NotUniqueUsernameException::class);
        Config::$USE_USERNAME = true;
        Config::$DENY_IF_NOT_VERIFIED_TO_SIGN_IN = false;
        $data = $this->getData();
        $data2 = [
            'email'=>'anotherEmail@email.com',
            'password'=>'this_is_password'
        ];
        $signUp = new SignUp($data);
        $signUp2 = new SignUp($data2);
        new SetUsername([
            'tempToken'=>$signUp->tempToken,
            'username'=>self::NEW_USERNAME
        ]);
        new SetUsername([
            'tempToken'=>$signUp2->tempToken,
            'username'=>'another_username'
        ]);
        $signIn = new SignIn($data2);
        new ChangeUsername([
            'jwt'=>$signIn->jwt,
            'newUsername'=>self::NEW_USERNAME
        ]);
    }
    private function getData() {
        return [
            'email'=>'email@email.com',
            'password'=>'this_is_password'
        ];
    }
}
