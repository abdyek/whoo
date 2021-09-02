<?php

require 'propel/config.php';
use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Controller\SignUp;
use Abdyek\Whoo\Controller\Example;
use Abdyek\Whoo\Exception\NotUniqueEmailException;

/**
 * @covers SignUp::
 */

class SignUpTest extends TestCase {
    use Reset;
    use UserTool;
    use ChangeConfig;
    public function setUp(): void {
        self::reset();
    }
    public function testRun() {
        $data = $this->getData();
        $config = $this->changeConfig([
            'USE_USERNAME'=>true
        ]);
        $signUp = new SignUp($data, $config);
        $this->assertNotNull($signUp->user);
        $this->assertEquals(60, strlen($signUp->temporaryToken));
    }
    /**
     * @dataProvider trueFalse
     */
    public function testRunDefault2FATrue($val) {
        $data = $this->getData();
        $config = $this->changeConfig([
            'USE_USERNAME'=>false,
            'DEFAULT_2FA'=>$val
        ]);
        $signUp = new SignUp($data, $config);
        $this->assertEquals($val ,$signUp->user->getTwoFactorAuthentication());
    }
    public function testRunNotUniqueEmailException() {
        $this->expectException(NotUniqueEmailException::class);
        $user = self::createExample();
        $data = $this->getData();
        $data['email'] = self::$traitEmail;
        $signUp = new SignUp($data);
    }
    public function testRunUseUsernameFalse() {
        $data = $this->getData();
        $config = $this->changeConfig([
            'USE_USERNAME'=>false
        ]);
        $signUp = new SignUp($data, $config);
        $this->assertNull($signUp->temporaryToken);
    }
    private function getData() {
        return [
            'email'=>'example@example.com',
            'username'=>'this is username',
            'password'=>'123123123121'
        ];
    }
    public function trueFalse() {
        return [
            [true],
            [false]
        ];
    }
}
