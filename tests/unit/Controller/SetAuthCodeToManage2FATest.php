<?php

require 'propel/config.php';
use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Controller\SetAuthCodeToManage2FA;
use Abdyek\Whoo\Controller\SignUp;
use Abdyek\Whoo\Controller\SignIn;
use Abdyek\Whoo\Model\AuthenticationCode;
use Abdyek\Whoo\Config\Authentication as AuthConfig;
use Abdyek\Whoo\Exception\IncorrectPasswordException;

/**
 * @covers SetAuthCodeToManage2FA::
 */

class SetAuthCodeToManage2FATest extends TestCase {
    use Reset;
    use ChangeConfig;
    public function setUp(): void {
        self::reset();
    }
    public function testRun() {
        $data = $this->getData();
        $config = $this->changeConfig([
            'USE_USERNAME'=>false,
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>false,
            'DEFAULT_2FA'=>false
        ]);
        new SignUp($data, $config);
        $signIn = new SignIn($data, $config);
        new SetAuthCodeToManage2FA([
            'jwt'=>$signIn->jwt,
            'password'=>$data['password']
        ], $config);
        $authCode = AuthenticationCode::getByUserIdType($signIn->user->getId(), AuthConfig::TYPE_MANAGE_2FA);
        $this->assertNotNull($authCode->getCode());
        $this->assertEquals(AuthConfig::SIZE_OF_CODE_TO_MANAGE_2FA, strlen($authCode->getCode()));
    }
    public function testRunIncorrectPasswordException() {
        $this->expectException(IncorrectPasswordException::class);
        $data = $this->getData();
        $config = $this->changeConfig([
            'USE_USERNAME'=>false,
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>false,
            'DEFAULT_2FA'=>false
        ]);
        new SignUp($data, $config);
        $signIn = new SignIn($data, $config);
        new SetAuthCodeToManage2FA([
            'jwt'=>$signIn->jwt,
            'password'=>'wrong-password'
        ], $config);
    }
    private function getData() {
        return [
            'email'=>'this_is_email@email.com',
            'password'=>'this-is-password'
        ];
    }
}
