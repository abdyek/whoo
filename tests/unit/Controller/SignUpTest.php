<?php

require 'propel/config.php';
use PHPUnit\Framework\TestCase;
use Whoo\Controller\SignUp;
use Whoo\Controller\Example;
use Whoo\Exception\NotUniqueEmailException;

/**
 * @covers SignUp::
 */

class SignUpTest extends TestCase {
    use Reset;
    use UserTool;
    public function setUp(): void {
        self::reset();
    }
    public function testRun() {
        $data = $this->getData();
        $signUp = new SignUp($data);
        $this->assertEquals(60, strlen($signUp->temporaryToken));
    }
    public function testRunNotUniqueEmailException() {
        $this->expectException(NotUniqueEmailException::class);
        $user = self::createExample();
        $data = $this->getData();
        $data['email'] = self::$traitEmail;
        $signUp = new SignUp($data);
    }
    private function getData() {
        return [
            'email'=>'example@example.com',
            'username'=>'this is username',
            'password'=>'123123123121'
        ];
    }
}
