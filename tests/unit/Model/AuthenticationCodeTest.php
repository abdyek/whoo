<?php

require 'propel/config.php';

use PHPUnit\Framework\TestCase;
use Whoo\Model\AuthenticationCode;
use Whoo\Tool\Random;

/**
 * @covers AuthenticationCode::
 */

class AuthenticationCodeTest extends TestCase {
    use Reset;
    use UserTool;
    public function setUp(): void {
        self::reset();
    }
    public function testCreate() {
        $user = $this->createExample();
        $type = '2FA';
        $code = Random::number(6);
        AuthenticationCode::create($user->getId(), $type, $code);
        $auth = \AuthenticationCodeQuery::create()->filterByUserId($user->getId())->findOneByType($type);
        $this->assertNotNull($auth);
        $this->assertEquals($user->getId(), $auth->getUserId());
        $this->assertEquals($type, $auth->getType());
        $this->assertEquals($code, $auth->getCode());
        $this->assertNotNull($auth->getDateTime());
    }
    public function testGetByUserIdType() {
        $user = $this->createExample();
        $type = 'verificationEmail';
        $code = Random::number(6);
        AuthenticationCode::create($user->getId(), $type, $code);
        $auth = AuthenticationCode::getByUserIdType($user->getId(), $type);
        $this->assertEquals($user->getId(), $auth->getUserId());
        $this->assertEquals($type, $auth->getType());
        $this->assertEquals($code, $auth->getCode());
    }
    public function testGetAllByUserId() {
        $user = $this->createExample();
        $auth1 = AuthenticationCode::create($user->getId(), 'type-1', '123123');
        $auth2 = AuthenticationCode::create($user->getId(), 'type-2', '123123');
        $auths = AuthenticationCode::getAllByUserId($user->getId());
        $this->assertCount(2, $auths);
    }
    public function testIncreaseTrialCount() {
        $user = $this->createExample();
        $type = '2fa';
        $code = Random::number(6);
        $auth = AuthenticationCode::create($user->getId(), $type, $code);
        for($i=0; $i<5; $i++) {
            $count = $auth->getTrialCount();
            AuthenticationCode::increaseTrialCount($auth);
            $this->assertEquals($count+1, $auth->getTrialCount());
        }
    }
    public function testDelete() {
        $user = $this->createExample();
        $auth = AuthenticationCode::create($user->getId(), '2fa', 'thisIsCode');
        AuthenticationCode::delete($auth);
        $auth = AuthenticationCode::getByUserIdType($user->getId(), '2fa');
        $this->assertNull($auth);
    }
    public function testDeleteByUserIdType() {
        $type = 'type';
        $user = $this->createExample();
        $auth = AuthenticationCode::create($user->getId(), $type, 'code');
        AuthenticationCode::deleteByUserIdType($user->getId(), $type);
        $auth = AuthenticationCode::getByUserIdType($user->getId(), $type);
        $this->assertNull($auth);
    }
    public function testDeleteAllByUserId() {
        $user = $this->createExample();
        $auth1 = AuthenticationCode::create($user->getId(), 'type-1', '123123');
        $auth2 = AuthenticationCode::create($user->getId(), 'type-2', '123123');
        AuthenticationCode::deleteAllByUserId($user->getId());
        $this->assertTrue($auth1->isDeleted());
        $this->assertTrue($auth2->isDeleted());
    }
}

