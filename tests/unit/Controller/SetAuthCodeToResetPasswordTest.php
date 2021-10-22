<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Controller\SignUp;
use Abdyek\Whoo\Controller\SetAuthCodeToResetPassword;
use Abdyek\Whoo\Config\Authentication as AuthConfig;
use Abdyek\Whoo\Exception\NotFoundException;
use Abdyek\Whoo\Exception\NotVerifiedEmailException;
use Abdyek\Whoo\Model\AuthenticationCode;
use Abdyek\Whoo\Config\Whoo as Config;
use Abdyek\Whoo\Config\Propel as PropelConfig;

/**
 * @covers SetAuthCodeToResetPassword::
 */

class SetAuthCodeToResetPasswordTest extends TestCase {
    use DefaultConfig;
    use Reset;
    public static function setUpBeforeClass(): void {
        PropelConfig::$CONFIG_FILE = 'propel/config.php';
    }
    public function setUp(): void {
        self::setDefaultConfig();
        self::reset();
    }
    public function testRun() {
        $data = $this->getData();
        Config::$USE_USERNAME = false;
        Config::$DENY_IF_NOT_VERIFIED_TO_RESET_PW = false;
        $signUp = new SignUp($data);
        $setAuth = new SetAuthCodeToResetPassword([
            'email'=>$data['email']
        ]);
        $this->assertEquals(AuthConfig::$SIZE_OF_CODE_TO_RESET_PW, strlen($setAuth->authCode));
    }
    public function testRunNotFoundException() {
        $this->expectException(NotFoundException::class);
        Config::$DENY_IF_NOT_VERIFIED_TO_RESET_PW = false;
        new SetAuthCodeToResetPassword([
            'email'=>'nothingness@nothingness.com'
        ]);
    }
    public function testRunAuthCode() {
        Config::$DENY_IF_NOT_VERIFIED_TO_RESET_PW = true;
        Config::$USE_USERNAME = false;
        $data = $this->getData();
        $signUp = new SignUp($data);
        try {
            new SetAuthCodeToResetPassword([
                'email'=>$data['email']
            ]);
        } catch (NotVerifiedEmailException $e) {
            $authCode = AuthenticationCode::getByUserIdType($signUp->user->getId(), AuthConfig::TYPE_EMAIL_VERIFICATION);
            $this->assertSame($authCode->getCode(), $e->authCode);
        }
    }
    public function testRunDenyIfNotVerifiedToResetPWTrue() {
        $this->expectException(NotVerifiedEmailException::class);
        $data = $this->getData();
        Config::$DENY_IF_NOT_VERIFIED_TO_RESET_PW = true;
        $signUp = new SignUp($data);
        new SetAuthCodeToResetPassword([
            'email'=>$data['email']
        ]);
    }
    private function getData() {
        return [
            'email'=>'thisIsEmail@test.com',
            'password'=>'thisIsPassword'
        ];
    }
}
