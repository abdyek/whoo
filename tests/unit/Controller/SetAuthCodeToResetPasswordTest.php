<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Tool\Config;
use Abdyek\Whoo\Controller\SignUp;
use Abdyek\Whoo\Controller\SetAuthCodeToResetPassword;
use Abdyek\Whoo\Config\Authentication as AuthConfig;
use Abdyek\Whoo\Exception\NotFoundException;
use Abdyek\Whoo\Exception\NotVerifiedEmailException;
use Abdyek\Whoo\Model\AuthenticationCode;

/**
 * @covers SetAuthCodeToResetPassword::
 */

class SetAuthCodeToResetPasswordTest extends TestCase {
    use Reset;
    use ChangeConfig;
    public static function setUpBeforeClass(): void {
        Config::setPropelConfigDir('propel/config.php');
        Config::load(); // for reset
    }
    public function setUp(): void {
        self::reset();
    }
    public function testRun() {
        $data = $this->getData();
        $config = $this->changeConfig([
            'USE_USERNAME'=>false,
            'DENY_IF_NOT_VERIFIED_TO_RESET_PW'=>false
        ]);
        $signUp = new SignUp($data, $config);
        $setAuth = new SetAuthCodeToResetPassword([
            'email'=>$data['email']
        ], $config);
        $this->assertEquals(AuthConfig::SIZE_OF_CODE_TO_RESET_PW, strlen($setAuth->code));
    }
    public function testRunNotFoundException() {
        $this->expectException(NotFoundException::class);
        $config = $this->changeConfig([
            'DENY_IF_NOT_VERIFIED_TO_RESET_PW'=>false
        ]);
        new SetAuthCodeToResetPassword([
            'email'=>'nothingness@nothingness.com'
        ], $config);
    }
    public function testRunDenyIfNotVerifiedToResetPWTrue() {
        $this->expectException(NotVerifiedEmailException::class);
        $data = $this->getData();
        $config = $this->changeConfig([
            'DENY_IF_NOT_VERIFIED_TO_RESET_PW'=>true
        ]);
        $signUp = new SignUp($data, $config);
        new SetAuthCodeToResetPassword([
            'email'=>$data['email']
        ], $config);
    }
    private function getData() {
        return [
            'email'=>'thisIsEmail@test.com',
            'password'=>'thisIsPassword'
        ];
    }
}
