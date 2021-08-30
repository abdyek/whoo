<?php

require 'propel/config.php';
use PHPUnit\Framework\TestCase;
use Whoo\Controller\SignUp;
use Whoo\Controller\SignInByUsername;
use Whoo\Controller\SetUsername;
use Whoo\Model\User as UserModel;
use Whoo\Model\AuthenticationCode;
use Whoo\Exception\IncorrectPasswordException;
use Whoo\Exception\NotFoundException;
use Whoo\Exception\NotVerifiedEmailException;
use Whoo\Exception\TwoFactorAuthEnabledException;

/**
 * @covers SignInByUsername::
 */

class SignInByUsernameTest extends TestCase {
    const USERNAME = 'uS3rN@mE';
    use Reset;
    use ChangeConfig;
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
        $config = $this->changeConfig([
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>true
        ]);
        $data = $this->getData();
        $signUp = new SignUp($data);
        $user = UserModel::getByEmail($data['email']);
        UserModel::setUsername($user, self::USERNAME);
        $signIn = new SignInByUsername([
            'username'=>self::USERNAME,
            'password'=>$data['password']
        ], $config);
    }
    public function testRun2FA() {
        $config = $this->changeConfig([
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>false,
            'USE_USERNAME'=>true,
            'DEFAULT_2FA'=>true
        ]);
        $data = $this->getData();
        $signUp = new SignUp($data, $config);
        new SetUsername([
            'temporaryToken'=>$signUp->temporaryToken,
            'username'=>self::USERNAME
        ], $config);
        try {
            $signIn = new SignInByUsername([
                'username'=>self::USERNAME,
                'password'=>$data['password']
            ], $config);
        } catch(TwoFactorAuthEnabledException $e) {
            $user = UserModel::getByUsername(self::USERNAME);
            $code = AuthenticationCode::getByUserIdType($user->getId(), '2FA-sign-in');
            $this->assertSame($code->getCode(), $e->authenticationCode);
        }
    }
    public function testRun2FAWithException() {
        $this->expectException(TwoFactorAuthEnabledException::class);
        $config = $this->changeConfig([
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>false,
            'USE_USERNAME'=>true,
            'DEFAULT_2FA'=>true
        ]);
        $data = $this->getData();
        $signUp = new SignUp($data, $config);
        new SetUsername([
            'temporaryToken'=>$signUp->temporaryToken,
            'username'=>self::USERNAME
        ], $config);
        $signIn = new SignInByUsername([
            'username'=>self::USERNAME,
            'password'=>$data['password']
        ], $config);
    }
    private function getData() {
        return [
            'email'=>'thisIsEmail@foo.com',
            'password'=>'this is password 123'
        ];
    }
}
