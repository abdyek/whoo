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
        $this->assertTrue($signUp->isSuccess);
        if($signUp->isSuccess) {
            $data = $this->getData();
            unset($data['username']);
            $data['who'] = 'member';
            $signIn = new SignIn($data);
            $this->assertTrue($signIn->isSuccess);
            $decoded = (array) JWT::decode($signIn->jwt, JWTConfig::SECRET_KEY, array('HS256'));
            $this->assertNotNull($decoded);
            $this->assertEquals($signIn->user->getId(), $decoded['userId']);
            $this->assertEquals($data['who'], $decoded['who']);
        }
    }
    public function testNotFoundException() {
        $this->expectException(NotFoundException::class);
        $data = $this->getData();
        unset($data['username']);
        $data['who'] = 'member';
        $data['email'] = 'notFoundEmail@123.com';
        $signIn = new SignIn($data);
    }
    public function testRunIncorrectPasswordException() {
        $this->expectException(IncorrectPasswordException::class);
        $signUp = new SignUp($this->getData());
        $this->assertTrue($signUp->isSuccess);
        if($signUp->isSuccess) {
            $data = $this->getData();
            unset($data['username']);
            $data['who'] = 'member';
            $data['password'] = 'wrong password';
            $signIn = new SignIn($data);
        }
    }
    private function getData() {
        return [
            'email'=>'example@example.com',
            'username'=>'this is username',
            'password'=>'this is too secret password'
        ];
    }
}
