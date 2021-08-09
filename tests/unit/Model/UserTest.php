<?php

require 'propel/config.php';

use PHPUnit\Framework\TestCase;
use Whoo\Model\User;

/**
 * @covers User::
 */

class UserTest extends TestCase {
    const USERNAME = 'this is example username';
    use Reset;
    use UserTool;
    public function setUp(): void {
        self::reset();
    }
    public function testIsUniqueEmail() {
        $user = $this->createExample();
        $this->assertFalse(User::isUniqueEmail(self::$traitEmail));
        $this->assertTrue(User::isUniqueEmail('another@email.com'));
    }
    public function testIsUniqueUsername() {
        $user = $this->createExample();
        $this->assertFalse(User::isUniqueUsername(self::$traitUsername));
        $this->assertTrue(User::isUniqueUsername('another username'));
    }
    public function testCreate() {
        $data = $this->getData();
        $user = User::create($data);
        $this->assertEquals($data['email'], $user->getEmail());
        $this->assertTrue(password_verify($data['password'], $user->getPasswordHash()));
        $this->assertFalse($user->getEmailVerified());
    }
    public function testCreateViaProvider() {
        $data = $this->getData();
        unset($data['password']);
        $data['provider'] = 'google';
        $data['providerId'] = '123123';
        $user = User::create($data);
        $this->assertEquals($data['email'], $user->getEmail());
        $this->assertEquals($data['provider'], $user->getProvider());
        $this->assertEquals($data['providerId'], $user->getProviderId());
        $this->assertTrue($user->getEmailVerified());
    }
    public function testGetByEmail() {
        $user= $this->createExample();
        $userFromModel = User::getByEmail($user->getEmail());
        $user2FromModel = User::getByEmail('willBeNull@abc.com');
        $this->assertNotNull($userFromModel);
        $this->assertSame(self::$traitEmail, $userFromModel->getEmail());
        $this->assertSame($user->getId(), $userFromModel->getId());
        $this->assertSame($user->getUsername(), $userFromModel->getUsername());
        $this->assertNull($user2FromModel);
    }
    public function testGetByUsername() {
        $user = $this->createExample();
        $user->setUsername(self::USERNAME);
        $user->save();
        $fetched = User::getByUsername(self::USERNAME);
        $this->assertNotNull($fetched);
        $this->assertSame(self::USERNAME, $user->getUsername());
    }
    public function testGetById() {
        $user = $this->createExample();
        $fetched = User::getById($user->getId());
        $this->assertNotNull($fetched);
        $this->assertSame($user->getId(), $fetched->getId());
    }
    public function testSetUsername() {
        $user= $this->createExample();
        User::setUsername($user, self::USERNAME);
        $this->assertSame(self::USERNAME, $user->getUsername());
    }
    public function testSetEmailVerified() {
        $user = $this->createExample();
        User::setEmailVerified($user, true);
        $this->assertTrue($user->getEmailVerified());
        User::setEmailVerified($user, false);
        $this->assertFalse($user->getEmailVerified());
    }
    private function getData() {
        return [
            'email'=>'example@example.com',
            'password'=>'s3cr3t',
        ];
    }
}
