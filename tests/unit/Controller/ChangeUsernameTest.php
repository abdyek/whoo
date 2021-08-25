<?php

require 'propel/config.php';
use PHPUnit\Framework\TestCase;
use Whoo\Controller\ChangeUsername;
use Whoo\Controller\SignUp;
use Whoo\Controller\SignIn;
use Whoo\Controller\SetUsername;
use Whoo\Exception\NotUniqueUsernameException;

/**
 * @covers ChangeUsername::
 */

class ChangeUsernameTest extends TestCase {
    use Reset;
    use ChangeConfig;
    const NEW_USERNAME = 'this_is_username';
    public function setUp(): void {
        self::reset();
    }
    public function testRun() {
        $config = $this->changeConfig([
            'USE_USERNAME'=>true,
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>false
        ]);
        $data = $this->getData();
        $signUp = new SignUp($data, $config);
        new SetUsername([
            'temporaryToken'=>$signUp->temporaryToken,
            'username'=>'username'
        ], $config);
        $signIn = new SignIn($data, $config);
        new ChangeUsername([
            'jwt'=>$signIn->jwt,
            'newUsername'=>self::NEW_USERNAME
        ], $config);
        $this->assertSame(self::NEW_USERNAME, $signIn->user->getUsername());
    }
    public function testRunNotUniqueUsernameException() {
        $this->expectException(NotUniqueUsernameException::class);
        $config = $this->changeConfig([
            'USE_USERNAME'=>true,
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>false
        ]);
        $data = $this->getData();
        $data2 = [
            'email'=>'anotherEmail@email.com',
            'password'=>'this_is_password'
        ];
        $signUp = new SignUp($data, $config);
        $signUp2 = new SignUp($data2, $config);
        new SetUsername([
            'temporaryToken'=>$signUp->temporaryToken,
            'username'=>self::NEW_USERNAME
        ], $config);
        new SetUsername([
            'temporaryToken'=>$signUp2->temporaryToken,
            'username'=>'another_username'
        ], $config);
        $signIn = new SignIn($data2, $config);
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
