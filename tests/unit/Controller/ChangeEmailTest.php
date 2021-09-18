<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Controller\SignUp;
use Abdyek\Whoo\Controller\SignIn;
use Abdyek\Whoo\Controller\ChangeEmail;
use Abdyek\Whoo\Controller\FetchInfo;
use Abdyek\Whoo\Model\User;
use Abdyek\Whoo\Exception\IncorrectPasswordException;
use Abdyek\Whoo\Exception\NotUniqueEmailException;
use Abdyek\Whoo\Exception\InvalidTokenException;
use Abdyek\Whoo\Config\Whoo as Config;
use Abdyek\Whoo\Config\Propel as PropelConfig;

/**
 * @covers ChangeEmail::
 */

class ChangeEmailTest extends TestCase {
    use Reset;
    public static function setUpBeforeClass(): void {
        PropelConfig::$CONFIG_FILE = 'propel/config.php';
    }
    public function setUp(): void {
        self::reset();
    }
    public function testRun() {
        $newEmail = 'new@new.com';
        $data = $this->getData();
        Config::$USE_USERNAME = false;
        Config::$DENY_IF_NOT_VERIFIED_TO_SIGN_IN = false;
        new SignUp($data);
        $signIn = new SignIn($data);
        new ChangeEmail([
            'jwt'=>$signIn->jwt,
            'newEmail'=>$newEmail,
            'password'=>$data['password']
        ]);
        $data['email'] = $newEmail;
        $signIn = new SignIn($data);
        $this->assertNotNull($signIn->jwt);
    }
    public function testRunIncorrectPasswordException() {
        $this->expectException(IncorrectPasswordException::class);
        $data = $this->getData();
        Config::$USE_USERNAME = false;
        Config::$DENY_IF_NOT_VERIFIED_TO_SIGN_IN = false;
        new SignUp($data);
        $signIn = new SignIn($data);
        new ChangeEmail([
            'jwt'=>$signIn->jwt,
            'newEmail'=>'newEmail@email.com',
            'password'=>'wrong_password'
        ]);
    }
    public function testRunNotUniqueEmailException() {
        $this->expectException(NotUniqueEmailException::class);
        $data = $this->getData();
        $data2 = [
            'email'=>'email2@email.com',
            'password'=>'this_is_password2'
        ];
        Config::$USE_USERNAME = false;
        Config::$DENY_IF_NOT_VERIFIED_TO_SIGN_IN = false;
        new SignUp($data);
        new SignUp($data2);
        $signIn = new SignIn($data);
        new ChangeEmail([
            'jwt'=>$signIn->jwt,
            'newEmail'=>$data2['email'],
            'password'=>$data['password']
        ]);
    }
    public function testRunSignOut() {
        $this->expectException(InvalidTokenException::class);
        $data = $this->getData();
        Config::$USE_USERNAME = false;
        Config::$DENY_IF_NOT_VERIFIED_TO_SIGN_IN = false;
        new SignUp($data);
        $signIn = new SignIn($data);
        new ChangeEmail([
            'jwt'=>$signIn->jwt,
            'newEmail'=>'newEmail@newEmail.com',
            'password'=>$data['password']
        ]);
        new FetchInfo([
            'jwt'=>$signIn->jwt
        ]);
    }
    private function getData() {
        return [
            'email'=>'email@email.com',
            'password'=>'this_is_password'
        ];
    }
}
