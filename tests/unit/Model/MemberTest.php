<?php

require 'propel/config.php';

use PHPUnit\Framework\TestCase;
use Whoo\Model\Member;

/**
 * @covers Member::
 */

class MemberTest extends TestCase {
    use Reset;
    use MemberTool;
    public function setUp(): void {
        self::reset();
    }
    public function testIsUniqueEmail() {
        $member = $this->createExample();
        $this->assertFalse(Member::isUniqueEmail(self::$traitEmail));
        $this->assertTrue(Member::isUniqueEmail('another@email.com'));
    }
    public function testIsUniqueUsername() {
        $member = $this->createExample();
        $this->assertFalse(Member::isUniqueUsername(self::$traitUsername));
        $this->assertTrue(Member::isUniqueUsername('another username'));
    }
    public function testCreate() {
        $data = $this->getData();
        $member = Member::create($data);
        $this->assertEquals($data['email'], $member->getEmail());
        $this->assertEquals($data['username'], $member->getUsername());
        $this->assertTrue(password_verify($data['password'], $member->getPasswordHash()));
        $this->assertFalse($member->getEmailVerified());
    }
    public function testCreateViaProvider() {
        $data = $this->getData();
        unset($data['password']);
        $data['provider'] = 'google';
        $data['providerId'] = '123123';
        $member = Member::create($data);
        $this->assertEquals($data['email'], $member->getEmail());
        $this->assertEquals($data['username'], $member->getUsername());
        $this->assertEquals($data['provider'], $member->getProvider());
        $this->assertEquals($data['providerId'], $member->getProviderId());
        $this->assertTrue($member->getEmailVerified());
    }
    private function getData() {
        return [
            'email'=>'example@example.com',
            'username'=>'this is username',
            'password'=>'s3cr3t',
        ];
    }
}
