<?php

require 'propel/config.php';
use PHPUnit\Framework\TestCase;
use Whoo\Controller\SignInByProvider;

/**
 * @covers SignInByProvider
 */

class SignInByProviderTest extends TestCase {
    use Reset;
    public function setUp(): void {
        self::reset();
    }
    public function testSignUpUseUsernameTrue() {
        $data = self::getData();
        $signUp = new SignInByProvider($data);
        $this->assertTrue($signUp->registering);
        $this->assertNotNull($signUp->jwt);
    }
    public function testSignUpUseUsernameFalse() {
        // I will fill it after to separate config files from the controller class
    }
    public function testSignInBlockIfSignUpBeforeByEmailTrue() {
        // I will fill it after to separate config files from the controller class
    }
    public function testSignInBlockIfSignUpBeforeByEmailFalse() {
        // I will fill it after to separate config files from the controller class
    }
    private function getData() {
        return [
            'email'=>'this_is_email@123.com',
            'provider'=>'google',
            'providerId'=>'this_is_provider_id'
        ];
    }
}

