<?php

require 'propel/config.php';
use PHPUnit\Framework\TestCase;
use Whoo\Controller\SignUp;
use Whoo\Controller\SignIn;
use Whoo\Exception\NotFoundException;
use Whoo\Exception\IncorrectPasswordException;
use Firebase\JWT\JWT;
use Whoo\Config\JWT as JWTConfig;

/**
 * @covers SignIn::
 */

class SignInTest extends TestCase {
    use Reset;
    public function setUp(): void {
        self::reset();
    }
    public function testRun() {
        $signUp = new SignUp($this->getData());
        $data = $this->getData();
        unset($data['username']);
        $signIn = new SignIn($data);
        $decoded = (array) JWT::decode($signIn->jwt, JWTConfig::SECRET_KEY, array('HS256'));
        $this->assertNotNull($decoded);
        $this->assertEquals($signIn->user->getId(), $decoded['userId']);
    }
    public function testNotFoundException() {
        $this->expectException(NotFoundException::class);
        $data = $this->getData();
        unset($data['username']);
        $data['email'] = 'notFoundEmail@123.com';
        $signIn = new SignIn($data);
    }
    public function testRunIncorrectPasswordException() {
        $this->expectException(IncorrectPasswordException::class);
        $signUp = new SignUp($this->getData());
        $data = $this->getData();
        unset($data['username']);
        $data['password'] = 'wrong password';
        $signIn = new SignIn($data);
    }
    private function getData() {
        return [
            'email'=>'example@example.com',
            'username'=>'this is username',
            'password'=>'this is too secret password'
        ];
    }
}
