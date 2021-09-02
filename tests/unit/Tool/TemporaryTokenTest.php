<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Tool\TemporaryToken;

/**
 * @covers TemporaryToken::
 */

class TemporaryTokenTest extends TestCase {
    const USERID = 15;
    public function testGenerate() {
        $tempToken = TemporaryToken::generate(12);
        $this->assertEquals(60, strlen($tempToken));
    }
    public function testGetUserId() {
        $tempToken = TemporaryToken::generate(self::USERID);
        $userId = TemporaryToken::getUserId($tempToken);
        $this->assertEquals(self::USERID, $userId);
        $userId = TemporaryToken::getUserId('wrong temp token');
        $this->assertNull($userId);
    }
}

