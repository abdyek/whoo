<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Repository\AuthenticationCode;
use Abdyek\Whoo\Tool\Random;
use Abdyek\Whoo\Config\Authentication as AuthConfig;
use Abdyek\Whoo\Config\Propel as PropelConfig;

/**
 * @covers AuthenticationCode::
 */

class AuthenticationCodeTest extends TestCase {
    use Reset;
    use UserTool;
    public static function setUpBeforeClass(): void {
        PropelConfig::$CONFIG_FILE = 'propel/config.php';
    }
    public function setUp(): void {
        self::reset();
    }
    public function testCreate() {
        $user = $this->createExample();
        $code = Random::number(6);
        AuthenticationCode::create($user->getId(), AuthConfig::TYPE_2FA, $code);
        $auth = \AuthenticationCodeQuery::create()->filterByUserId($user->getId())->findOneByType(AuthConfig::TYPE_2FA);
        $this->assertNotNull($auth);
        $this->assertEquals($user->getId(), $auth->getUserId());
        $this->assertEquals(AuthConfig::TYPE_2FA, $auth->getType());
        $this->assertEquals($code, $auth->getCode());
        $this->assertNotNull($auth->getDateTime());
    }
    public function testGetByUserIdType() {
        $user = $this->createExample();
        $code = Random::number(6);
        AuthenticationCode::create($user->getId(), AuthConfig::TYPE_EMAIL_VERIFICATION, $code);
        $auth = AuthenticationCode::getByUserIdType($user->getId(), AuthConfig::TYPE_EMAIL_VERIFICATION);
        $this->assertEquals($user->getId(), $auth->getUserId());
        $this->assertEquals(AuthConfig::TYPE_EMAIL_VERIFICATION, $auth->getType());
        $this->assertEquals($code, $auth->getCode());
    }
    public function testGetAllByUserId() {
        $user = $this->createExample();
        $auth1 = AuthenticationCode::create($user->getId(), AuthConfig::TYPE_MANAGE_2FA, '123123');
        $auth2 = AuthenticationCode::create($user->getId(), AuthConfig::TYPE_2FA, '123123');
        $auths = AuthenticationCode::getAllByUserId($user->getId());
        $this->assertCount(2, $auths);
    }
    public function testIncreaseTrialCount() {
        $user = $this->createExample();
        $code = Random::number(6);
        $auth = AuthenticationCode::create($user->getId(), AuthConfig::TYPE_2FA, $code);
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
        $user = $this->createExample();
        $auth = AuthenticationCode::create($user->getId(), AuthConfig::TYPE_RESET_PW, 'code');
        AuthenticationCode::deleteByUserIdType($user->getId(), AuthConfig::TYPE_RESET_PW);
        $auth = AuthenticationCode::getByUserIdType($user->getId(), AuthConfig::TYPE_RESET_PW);
        $this->assertNull($auth);
    }
    public function testDeleteAllByUserId() {
        $user = $this->createExample();
        $auth1 = AuthenticationCode::create($user->getId(), AuthConfig::TYPE_MANAGE_2FA, '123123');
        $auth2 = AuthenticationCode::create($user->getId(), AuthConfig::TYPE_2FA, '123123');
        AuthenticationCode::deleteAllByUserId($user->getId());
        $this->assertTrue($auth1->isDeleted());
        $this->assertTrue($auth2->isDeleted());
    }
}

