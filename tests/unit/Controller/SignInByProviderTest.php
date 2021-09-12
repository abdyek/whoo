<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Tool\Config;
use Abdyek\Whoo\Controller\SignInByProvider;
use Abdyek\Whoo\Controller\SignUp;
use Abdyek\Whoo\Exception\NullUsernameException;
use Abdyek\Whoo\Exception\SignUpByEmailException;
use Abdyek\Whoo\Model\User as UserModel;

/**
 * @covers SignInByProvider::
 */

class SignInByProviderTest extends TestCase {
    use Reset;
    use ChangeConfig;
    public static function setUpBeforeClass(): void {
        Config::setPropelConfigDir('propel/config.php');
        Config::load(); // for reset
    }
    public function setUp(): void {
        self::reset();
    }
    public function testSignUpUseUsernameTrueException() {
        $this->expectException(NullUsernameException::class);
        $newConfig = $this->changeConfig(['USE_USERNAME'=>true]);
        $dataForProvider = self::getDataForProvider();
        $signUp = new SignInByProvider($dataForProvider, $newConfig);
        $user = UserModel::getByEmail($dataForProvider['email']);
        $this->assertTrue($signUp->registering);
        $this->assertNotNull($user->getId());
        $this->assertNull($signUp->jwt);
    }
    public function testSignUpUseUsernameFalseOK() {
        $newConfig = $this->changeConfig(['USE_USERNAME'=>false]);
        $dataForProvider = self::getDataForProvider();
        $signUp = new SignInByProvider($dataForProvider, $newConfig);
        $this->assertTrue($signUp->registering);
        $this->assertNotNull($signUp->jwt);
    }
    public function testSignInBlockIfSignUpBeforeByEmailTrueOK() {
        $newConfig = $this->changeConfig([
            'USE_USERNAME'=>false,
            'DENY_IF_SIGN_UP_BEFORE_BY_EMAIL'=>true
        ]);
        $dataForProvider = self::getDataForProvider();
        $signUpIn= new SignInByProvider($dataForProvider, $newConfig);
        $this->assertTrue($signUpIn->registering);
        $this->assertNotNull($signUpIn->jwt);
    }
    public function testSignInBlockIfSignUpBeforeByEmailTrueException() {
        $this->expectException(SignUpByEmailException::class);
        $data = $this->getData();
        $newConfig = $this->changeConfig([
            'USE_USERNAME'=>false,
            'DENY_IF_SIGN_UP_BEFORE_BY_EMAIL'=>true
        ]);
        $signUpNormally = new SignUp($data, $newConfig);
        $dataForProvider = $this->getDataForProvider();
        $signIn = new SignInByProvider($dataForProvider, $newConfig);
    }
    public function testSignInBlockIfSignUpBeforeByEmailFalse() {
        $newConfig = $this->changeConfig([
            'USE_USERNAME'=>false,
            'DENY_IF_SIGN_UP_BEFORE_BY_EMAIL'=>false
        ]);
        $data = self::getData();
        $signUp = new SignUp($data, $newConfig);
        $dataForProvider = self::getDataForProvider();
        $signIn = new SignInByProvider($dataForProvider, $newConfig);
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
