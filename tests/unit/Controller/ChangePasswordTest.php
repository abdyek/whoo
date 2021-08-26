<?php

require 'propel/config.php';
use PHPUnit\Framework\TestCase;
use Whoo\Controller\ChangePassword;
use Whoo\Controller\SignUp;
use Whoo\Controller\SignIn;
use Whoo\Exception\IncorrectPasswordException;

/**
 * @covers ChangePassword::
 */

class ChangePasswordTest extends TestCase {
    const NEW_PASSWORD = 'nEw_pAsSwOrD';
    use Reset;
    use ChangeConfig;
    public function setUp(): void {
        self::reset();
    }
    public function testRun() {
        $data = $this->getData();
        $config = $this->changeConfig([
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>false,
            'USE_USERNAME'=>false
        ]);
        new SignUp($data, $config);
        $signIn = new SignIn($data, $config);
        new ChangePassword([
            'jwt'=>$signIn->jwt,
            'password'=>$data['password'],
            'newPassword'=>self::NEW_PASSWORD
        ]);
        $data['password'] = self::NEW_PASSWORD;
        $signIn = new SignIn($data, $config);
        $this->assertNotNull($signIn);
    }
    public function testRunIncorrectPasswordException() {
        $this->expectException(IncorrectPasswordException::class);
        $data = $this->getData();
        $config = $this->changeConfig([
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>false,
            'USE_USERNAME'=>false
        ]);
        new SignUp($data, $config);
        $signIn = new SignIn($data, $config);
        new ChangePassword([
            'jwt'=>$signIn->jwt,
            'password'=>'wrongPassword :( ',
            'newPassword'=>'thisIsNewPassword'
        ]);
    }
    private function getData() {
        return [
            'email'=>'emailExample@email.com',
            'password'=>'this_is_password'
        ];
    }
}
        
