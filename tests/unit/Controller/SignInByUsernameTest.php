<?php

require 'propel/config.php';
use PHPUnit\Framework\TestCase;
use Whoo\Controller\SignUp;
use Whoo\Controller\SignInByUsername;
use Whoo\Model\User as UserModel;
use Whoo\Exception\IncorrectPasswordException;
use Whoo\Exception\NotFoundException;
use Whoo\Exception\NotVerifiedEmailException;

/**
 * @covers SignInByUsername::
 */

class SignInByUsernameTest extends TestCase {
    const USERNAME = 'uS3rN@mE';
    use Reset;
    public function setUp(): void {
        self::reset();
    }
    public function testRun() {
        $data = $this->getData();
        $signUp = new SignUp($data);
        $user= UserModel::getByEmail($data['email']);
        UserModel::setUsername($user, self::USERNAME);
        UserModel::setEmailVerified($user, true);
        $signIn = new SignInByUsername([
            'username'=>self::USERNAME,
            'password'=>$data['password']
        ]);
        $this->assertNotNull($signIn->jwt);
        $this->assertSame(self::USERNAME, $signIn->user->getUsername());
    }
    public function testRunIncorrectPasswordException() {
        $this->expectException(IncorrectPasswordException::class);
        $data = $this->getData();
        $signUp = new SignUp($data);
        $user = UserModel::getByEmail($data['email']);
        UserModel::setUsername($user, self::USERNAME);
        UserModel::setEmailVerified($user, true);
        $signIn = new SignInByUsername([
            'username'=>self::USERNAME,
            'password'=>'wR0ng paSsWorD'
        ]);
    }
    public function testRunNotFoundException() {
        $this->expectException(NotFoundException::class);
        $data = $this->getData();
        $signIn = new SignInByUsername([
            'username'=>'not_found_username',
            'password'=>'not_found_paSsWorD'
        ]);
    }
    public function testRunNotVerifiedEmailException() {
        $this->expectException(NotVerifiedEmailException::class);
        $data = $this->getData();
        $signUp = new SignUp($data);
        $user = UserModel::getByEmail($data['email']);
        UserModel::setUsername($user, self::USERNAME);
        $signIn = new SignInByUsername([
            'username'=>self::USERNAME,
            'password'=>$data['password']
        ]);
    }
    private function getData() {
        return [
            'email'=>'thisIsEmail@foo.com',
            'password'=>'this is password 123'
        ];
    }
}
