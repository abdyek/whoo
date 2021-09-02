<?php

require 'propel/config.php';

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Model\User;
use Abdyek\Whoo\Model\AuthenticationCode;

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
    public function testCreateOpen2FA() {
        $data = $this->getData();
        $data['twoFactorAuthentication'] = true;
        $user = User::create($data);
        $this->assertTrue($user->getTwoFactorAuthentication());
    }
    public function testCreateClose2FA() {
        $data = $this->getData();
        $data['twoFactorAuthentication'] = false;
        $user = USER::create($data);
        $this->assertFalse($user->getTwoFactorAuthentication());
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
    public function testIncreaseSignOutCount() {
        $user = $this->createExample();
        $defaultCount = $user->getSignOutCount();
        User::increaseSignOutCount($user);
        $this->assertEquals($defaultCount+1, $user->getSignOutCount());
    }
    public function testSetPassword() {
        $newPw = 'new_password';
        $data = $this->getData();
        $user = User::create($data);
        User::setPassword($user, $newPw);
        $hash = $user->getPasswordHash();
        $this->assertTrue(password_verify($newPw, $hash));
    }
    public function testCheckPassword() {
        $data = $this->getData();
        $user = User::create($data);
        $this->assertTrue(User::checkPassword($user, $data['password']));
        $this->assertFalse(User::checkPassword($user, 'wrongPw'));
    }
    public function testSetEmail() {
        $newEmail = 'newEmail@newEmail.com';
        $data = $this->getData();
        $user = User::create($data);
        User::setEmail($user, $newEmail);
        $this->assertEquals($newEmail, $user->getEmail());
    }
    public function testSet2FA() {
        $data = $this->getData();
        $user = User::create($data);
        User::set2FA($user, false);
        $this->assertFalse($user->getTwoFactorAuthentication());
    }
    public function testDeleteWithAll() {
        $data = $this->getData();
        $user = User::create($data);
        $auth = AuthenticationCode::create($user->getId(), 'type', '123123');
        User::deleteWithAll($user);
        $this->assertTrue($user->isDeleted());
        $this->assertTrue($auth->isDeleted());
    }
    private function getData() {
        return [
            'email'=>'example@example.com',
            'password'=>'s3cr3t',
        ];
    }
}
