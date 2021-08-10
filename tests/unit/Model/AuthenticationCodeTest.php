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
}

