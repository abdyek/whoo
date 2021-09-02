<?php

require 'propel/config.php';
use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Controller\SignUp;
use Abdyek\Whoo\Controller\SignIn;
use Abdyek\Whoo\Controller\ChangeEmail;
use Abdyek\Whoo\Controller\FetchInfo;
use Abdyek\Whoo\Model\User;
use Abdyek\Whoo\Exception\IncorrectPasswordException;
use Abdyek\Whoo\Exception\NotUniqueEmailException;
use Abdyek\Whoo\Exception\InvalidTokenException;

/**
 * @covers ChangeEmail::
 */

class ChangeEmailTest extends TestCase {
    use Reset;
    use ChangeConfig;
    public function setUp(): void {
        self::reset();
    }
    public function testRun() {
        $newEmail = 'new@new.com';
        $data = $this->getData();
        $config = $this->changeConfig([
            'USE_USERNAME'=>false,
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>false
        ]);
        new SignUp($data, $config);
        $signIn = new SignIn($data, $config);
        new ChangeEmail([
            'jwt'=>$signIn->jwt,
            'newEmail'=>$newEmail,
            'password'=>$data['password']
        ], $config);
        $data['email'] = $newEmail;
        $signIn = new SignIn($data, $config);
        $this->assertNotNull($signIn->jwt);
    }
    public function testRunIncorrectPasswordException() {
        $this->expectException(IncorrectPasswordException::class);
        $data = $this->getData();
        $config = $this->changeConfig([
            'USE_USERNAME'=>false,
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>false
        ]);
        new SignUp($data, $config);
        $signIn = new SignIn($data, $config);
        new ChangeEmail([
            'jwt'=>$signIn->jwt,
            'newEmail'=>'newEmail@email.com',
            'password'=>'wrong_password'
        ], $config);
    }
    public function testRunNotUniqueEmailException() {
        $this->expectException(NotUniqueEmailException::class);
        $data = $this->getData();
        $data2 = [
            'email'=>'email2@email.com',
            'password'=>'this_is_password2'
        ];
        $config = $this->changeConfig([
            'USE_USERNAME'=>false,
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>false
        ]);
        new SignUp($data, $config);
        new SignUp($data2, $config);
        $signIn = new SignIn($data, $config);
        new ChangeEmail([
            'jwt'=>$signIn->jwt,
            'newEmail'=>$data2['email'],
            'password'=>$data['password']
        ]);
    }
    public function testRunSignOut() {
        $this->expectException(InvalidTokenException::class);
        $data = $this->getData();
        $config = $this->changeConfig([
            'USE_USERNAME'=>false,
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>false
        ]);
        new SignUp($data, $config);
        $signIn = new SignIn($data, $config);
        new ChangeEmail([
            'jwt'=>$signIn->jwt,
            'newEmail'=>'newEmail@newEmail.com',
            'password'=>$data['password']
        ], $config);
        new FetchInfo([
            'jwt'=>$signIn->jwt
        ], $config);
    }
    private function getData() {
        return [
            'email'=>'email@email.com',
            'password'=>'this_is_password'
        ];
    }
}
