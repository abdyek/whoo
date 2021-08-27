<?php

require 'propel/config.php';
use PHPUnit\Framework\TestCase;
use Whoo\Controller\SetAuthCodeToManage2FA;
use Whoo\Controller\SignUp;
use Whoo\Controller\SignIn;
use Whoo\Model\AuthenticationCode;
use Whoo\Config\Authentication as AuthConfig;

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
        ]);
        new SignUp($data, $config);
        $signIn = new SignIn($data, $config);
        new SetAuthCodeToManage2FA([
            'jwt'=>$signIn->jwt,
        ], $config);
        $authCode = AuthenticationCode::getByUserIdType($signIn->user->getId(), '2FA');
        $this->assertNotNull($authCode->getCode());
        $this->assertEquals(AuthConfig::SIZE_OF_CODE_FOR_MANAGE_2FA, strlen($authCode->getCode()));
    }
    private function getData() {
        return [
            'email'=>'this_is_email@email.com',
            'password'=>'this-is-password'
        ];
    }
}
