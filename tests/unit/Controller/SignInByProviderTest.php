<?php

require 'propel/config.php';
use PHPUnit\Framework\TestCase;
use Whoo\Controller\SignInByProvider;
use Whoo\Controller\SignUp;
use Whoo\Exception\NullUsernameException;
use Whoo\Exception\SignUpByEmailException;
use Whoo\Model\Member as MemberModel;

/**
 * @covers SignInByProvider::
 */

class SignInByProviderTest extends TestCase {
    use Reset;
    use ChangeConfig;
    public function setUp(): void {
        self::reset();
    }
    public function testSignUpUseUsernameTrueException() {
        $this->expectException(NullUsernameException::class);
        $newConfig = $this->changeConfig(['USE_USERNAME'=>true]);
        $dataForProvider = self::getDataForProvider();
        $signUp = new SignInByProvider($dataForProvider, $newConfig);
        $member = MemberModel::getByEmail($dataForProvider['email']);
        $this->assertTrue($signUp->registering);
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
            'BLOCK_IF_SIGN_UP_BEFORE_BY_EMAIL'=>true
        ]);
        $dataForProvider = self::getDataForProvider();
        $signUp = new SignInByProvider($dataForProvider, $newConfig);
        $signIn = new SignInByProvider($dataForProvider, $newConfig);
        $this->assertTrue($signUp->registering);
        $this->assertFalse($signIn->registering);
        $this->assertNotNull($signUp->jwt);
    }
    public function testSignInBlockIfSignUpBeforeByEmailTrueException() {
        $this->expectException(SignUpByEmailException::class);
        $data = $this->getData();
        $newConfig = $this->changeConfig([
            'USE_USERNAME'=>false,
            'BLOCK_IF_SIGN_UP_BEFORE_BY_EMAIL'=>true
        ]);
        $signUpNormally = new SignUp($data, $newConfig);
        $dataForProvider = $this->getDataForProvider();
        $signIn = new signInByProvider($dataForProvider, $newConfig);
    }
    public function testSignInBlockIfSignUpBeforeByEmailFalse() {
        // NOT completed
        $this->assertEquals(1,1);
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
