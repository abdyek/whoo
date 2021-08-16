<?php

require 'propel/config.php';
use PHPUnit\Framework\TestCase;
use Whoo\Controller\SignUp;
use Whoo\Controller\SignIn;
use Whoo\Controller\SetUsername;
use Whoo\Exception\NotFoundException;
use Whoo\Exception\IncorrectPasswordException;
use Whoo\Exception\NotVerifiedEmailException;
use Whoo\Exception\NullUsernameException;
use Firebase\JWT\JWT;
use Whoo\Config\JWT as JWTConfig;

/**
 * @covers SignIn::
 */

class SignInTest extends TestCase {
    const USERNAME = 'usernamee';
    use Reset;
    use ChangeConfig;
    public function setUp(): void {
        self::reset();
    }
    public function testRun() {
        $data = $this->getData();
        $signUp = new SignUp($data);
        $config = $this->changeConfig([
            'BLOCK_NOT_VERIFIED'=>false,
            'USE_USERNAME'=>false
        ]);
        $signIn = new SignIn($data, $config);
        $decoded = (array) JWT::decode($signIn->jwt, JWTConfig::SECRET_KEY, array('HS256'));
        $this->assertNotNull($decoded);
        $this->assertNotNull($signIn->user);
        $this->assertNull($signIn->temporaryToken);
        $this->assertEquals($signIn->user->getId(), $decoded['userId']);
        $signIn = new SignIn($data, $config);
    }
    public function testRunUseUsernameTrue() {
        $data = $this->getData();
        $config = $this->changeConfig([
            'BLOCK_NOT_VERIFIED'=>false,
            'USE_USERNAME'=>true
        ]);
        $signUp = new SignUp($data, $config);
        new SetUsername([
            'temporaryToken'=>$signUp->temporaryToken,
            'username'=>self::USERNAME
        ], $config);
        $signIn = new SignIn($data, $config);
        $decoded = (array) JWT::decode($signIn->jwt, JWTConfig::SECRET_KEY, array('HS256'));
        $this->assertNotNull($decoded);
        $this->assertNotNull($signIn->user);
        $this->assertEquals($signIn->user->getId(), $decoded['userId']);
        $this->assertSame(self::USERNAME, $signIn->user->getUsername());
        $this->assertNull($signIn->temporaryToken);
    }
    public function testNotFoundException() {
        $this->expectException(NotFoundException::class);
        $data = $this->getData();
        $data['email'] = 'notFoundEmail@123.com';
        $signIn = new SignIn($data);
    }
    public function testRunIncorrectPasswordException() {
        $this->expectException(IncorrectPasswordException::class);
        $data = $this->getData();
        $signUp = new SignUp($data);
        $data['password'] = 'wrong password';
        $signIn = new SignIn($data);
    }
    public function testRunNotVerifiedEmailException() {
        $this->expectException(NotVerifiedEmailException::class);
        $data = $this->getData();
        $signUp = new SignUp($data);
        $config = $this->changeConfig([
            'BLOCK_NOT_VERIFIED'=>true,
            'USE_USERNAME'=>false
        ]);
        new SignIn($data, $config);
    }
    public function testRunNullUsernameException() {
        $this->expectException(NullUsernameException::class);
        $data = $this->getData();
        $signUp = new SignUp($data);
        $config = $this->changeConfig([
            'BLOCK_NOT_VERIFIED'=>false,
            'USE_USERNAME'=>true
        ]);
        new SignIn($data, $config);
    }
    private function getData() {
        return [
            'email'=>'example@example.com',
            'password'=>'this is too secret password'
        ];
    }
}
