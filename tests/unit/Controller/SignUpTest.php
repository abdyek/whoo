<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Tool\Config;
use Abdyek\Whoo\Controller\SignUp;
use Abdyek\Whoo\Controller\Example;
use Abdyek\Whoo\Exception\NotUniqueEmailException;
use Abdyek\Whoo\Exception\NotUniqueUsernameException;
use Abdyek\Whoo\Exception\UnmatchedPasswordsException;
use Abdyek\Whoo\Model\User;

/**
 * @covers SignUp::
 */

class SignUpTest extends TestCase {
    private const USERNAME = 'thisIsUsername';
    use Reset;
    use UserTool;
    use ChangeConfig;
    public static function setUpBeforeClass(): void {
        Config::setPropelConfigDir('propel/config.php');
        Config::load(); // for reset
    }
    public function setUp(): void {
        self::reset();
    }
    public function testRun() {
        $data = $this->getData();
        $config = $this->changeConfig([
            'USE_USERNAME'=>true
        ]);
        $signUp = new SignUp($data, $config);
        $this->assertNotNull($signUp->user);
        $this->assertEquals(60, strlen($signUp->temporaryToken));
    }
    /**
     * @dataProvider trueFalse
     */
    public function testRunDefault2FATrue($val) {
        $data = $this->getData();
        $config = $this->changeConfig([
            'USE_USERNAME'=>false,
            'DEFAULT_2FA'=>$val
        ]);
        $signUp = new SignUp($data, $config);
        $this->assertEquals($val ,$signUp->user->getTwoFactorAuthentication());
    }
    public function testRunNotUniqueEmailException() {
        $this->expectException(NotUniqueEmailException::class);
        $user = self::createExample();
        $data = $this->getData();
        $data['email'] = self::$traitEmail;
        $signUp = new SignUp($data);
    }
    public function testRunUseUsernameFalse() {
        $data = $this->getData();
        $config = $this->changeConfig([
            'USE_USERNAME'=>false
        ]);
        $signUp = new SignUp($data, $config);
        $this->assertNull($signUp->temporaryToken);
    }
    public function testRunOptionalPasswordAgain() {
        $data = $this->getData();
        $config = $this->changeConfig([
            'USE_USERNAME'=>false,
            'DEFAULT_2FA'=>false,
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>false
        ]);
        $data['passwordAgain'] = $data['password'];
        $signUp = new SignUp($data, $config);
        $user = User::getByEmail($data['email']);
        $this->assertSame($user, $signUp->user);
    }
    public function testRunUnmatchedPasswordsException() {
        $this->expectException(UnmatchedPasswordsException::class);
        $data = $this->getData();
        $config = $this->changeConfig([
            'USE_USERNAME'=>false,
            'DEFAULT_2FA'=>false,
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>false
        ]);
        $data['passwordAgain'] = 'wrong_password';
        $signUp = new SignUp($data, $config);
    }
    public function testRunOptionalUsername() {
        $data = $this->getData();
        $config = $this->changeConfig([
            'USE_USERNAME'=>true,
            'DEFAULT_2FA'=>false,
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>false
        ]);
        $data['username'] = self::USERNAME;
        $signUp = new SignUp($data, $config);
        $user = User::getByEmail($data['email']);
        $this->assertEquals(self::USERNAME, $user->getUsername());
    }
    public function testRunNotUniqueUsernameException() {
        $this->expectException(NotUniqueUsernameException::class);
        $data = $this->getData();
        $config = $this->changeConfig([
            'USE_USERNAME'=>true,
            'DEFAULT_2FA'=>false,
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>false
        ]);
        $data['username'] = self::USERNAME;
        $anotherUserData = [
            'email'=>'another@another.com',
            'password'=>'this_is_pw',
            'username'=>self::USERNAME
        ];
        new SignUp($anotherUserData, $config);
        new SignUp($data, $config);
    }
    private function getData() {
        return [
            'email'=>'example@example.com',
            'password'=>'123123123121'
        ];
    }
    public function trueFalse() {
        return [
            [true],
            [false]
        ];
    }
}
