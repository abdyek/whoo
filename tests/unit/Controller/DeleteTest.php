<?php

require 'propel/config.php';
use PHPUnit\Framework\TestCase;
use Whoo\Controller\Delete;
use Whoo\Controller\SignUp;
use Whoo\Controller\SignIn;
use Whoo\Exception\IncorrectPasswordException;

/**
 * @covers Delete::
 */

class DeleteTest extends TestCase {
    use Reset;
    use ChangeConfig;
    public function setUp(): void {
        self::reset();
    }
    public function testRun() {
        $config = $this->changeConfig([
            'USE_USERNAME'=>false,
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>false,
            'DEFAULT_2FA'=>false
        ]);
        $data = $this->getData();
        new SignUp($data, $config);
        $signIn = new SignIn($data, $config);
        new Delete([
            'jwt'=>$signIn->jwt,
            'password'=>$data['password']
        ]);
        $this->assertTrue($signIn->user->isDeleted());
    }
    public function testRunIncorrectPasswordException() {
        $this->expectException(IncorrectPasswordException::class);
        $config = $this->changeConfig([
            'USE_USERNAME'=>false,
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>false,
            'DEFAULT_2FA'=>false
        ]);
        $data = $this->getData();
        new SignUp($data, $config);
        $signIn = new SignIn($data, $config);
        new Delete([
            'jwt'=>$signIn->jwt,
            'password'=>'wrong-password'
        ], $config);
    }
    private function getData() {
        return [
            'email'=>'example@example.com',
            'password'=>'this_is_password'
        ];
    }
}
