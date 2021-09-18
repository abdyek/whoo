<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Controller\SignInByProvider;
use Abdyek\Whoo\Controller\SignUp;
use Abdyek\Whoo\Exception\NullUsernameException;
use Abdyek\Whoo\Exception\SignUpByEmailException;
use Abdyek\Whoo\Model\User as UserModel;
use Abdyek\Whoo\Config\Whoo as Config;
use Abdyek\Whoo\Config\Propel as PropelConfig;

/**
 * @covers SignInByProvider::
 */

class SignInByProviderTest extends TestCase {
    use Reset;
    public static function setUpBeforeClass(): void {
        PropelConfig::$CONFIG_FILE = 'propel/config.php';
    }
    public function setUp(): void {
        self::reset();
    }
    public function testSignUpUseUsernameTrueException() {
        $this->expectException(NullUsernameException::class);
        $dataForProvider = self::getDataForProvider();
        Config::$USE_USERNAME = true;
        $signUp = new SignInByProvider($dataForProvider);
        $user = UserModel::getByEmail($dataForProvider['email']);
        $this->assertTrue($signUp->registering);
        $this->assertNotNull($user->getId());
        $this->assertNull($signUp->jwt);
    }
    public function testSignUpUseUsernameFalseOK() {
        Config::$USE_USERNAME = false;
        $dataForProvider = self::getDataForProvider();
        $signUp = new SignInByProvider($dataForProvider);
        $this->assertTrue($signUp->registering);
        $this->assertNotNull($signUp->jwt);
    }
    public function testSignInBlockIfSignUpBeforeByEmailTrueOK() {
        Config::$USE_USERNAME = false;
        Config::$DENY_IF_SIGN_UP_BEFORE_BY_EMAIL = true;
        $dataForProvider = self::getDataForProvider();
        $signUpIn= new SignInByProvider($dataForProvider);
        $this->assertTrue($signUpIn->registering);
        $this->assertNotNull($signUpIn->jwt);
    }
    public function testSignInBlockIfSignUpBeforeByEmailTrueException() {
        $this->expectException(SignUpByEmailException::class);
        $data = $this->getData();
        Config::$USE_USERNAME = false;
        Config::$DENY_IF_SIGN_UP_BEFORE_BY_EMAIL = true;
        $signUpNormally = new SignUp($data);
        $dataForProvider = $this->getDataForProvider();
        $signIn = new SignInByProvider($dataForProvider);
    }
    public function testSignInBlockIfSignUpBeforeByEmailFalse() {
        Config::$USE_USERNAME = false;
        Config::$DENY_IF_SIGN_UP_BEFORE_BY_EMAIL = false;
        $data = self::getData();
        $signUp = new SignUp($data);
        $dataForProvider = self::getDataForProvider();
        $signIn = new SignInByProvider($dataForProvider);
        $this->assertNotNull($signIn->jwt);
    }
    private function getData() {
        return [
            'email'=>'must_be_same_email@test.com',
            'password'=>'this_is_password'
        ];
    }
    private function getDataForProvider() {
        return [
            'email'=>'must_be_same_email@test.com',
            'provider'=>'google',
            'providerId'=>'this_is_provider_id'
        ];
    }
}
