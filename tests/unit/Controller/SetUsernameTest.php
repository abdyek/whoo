<?php

require 'propel/config.php';
use PHPUnit\Framework\TestCase;
use Whoo\Controller\SetUsername;
use Whoo\Controller\SignUp;
use Whoo\Model\Member as MemberModel;

/**
 * @covers SetUsername::
 */

class SetUsernameTest extends TestCase {
    const USERNAME = 'uSeRNaMe';
    use Reset;
    public function setUp(): void {
        self::reset();
    }
    public function testRun() {
        $data = $this->getData();
        $signUp = new SignUp($data);
        $this->assertEquals(60, strlen($signUp->temporaryToken));
        new SetUsername([
            'temporaryToken'=>$signUp->temporaryToken,
            'username'=>'uSerNaMe'
        ]);
        $member = MemberModel::getByEmail($data['email']);
        $this->assertSame(self::USERNAME, $member->getUsername());
    }
    private function getData() {
        return [
            'email'=>'abc@bcd.com',
            'password'=>'123123123q'
        ];
    }
}
