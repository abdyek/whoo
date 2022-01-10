<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Tool\TemporaryToken;

/**
 * @covers TemporaryToken::
 */

class TemporaryTokenTest extends TestCase
{
    const USERID = 15;
    const SECRET_KEY = 'secret';
    public function testGenerate()
    {
        $tempToken = TemporaryToken::generate(12, self::SECRET_KEY);
        $this->assertEquals(60, strlen($tempToken));
    }
    public function testGetUserId()
    {
        $tempToken = TemporaryToken::generate(self::USERID, self::SECRET_KEY);
        $userId = TemporaryToken::getUserId($tempToken, self::SECRET_KEY);
        $this->assertEquals(self::USERID, $userId);
        $userId = TemporaryToken::getUserId('wrong temp token', self::SECRET_KEY);
        $this->assertNull($userId);
    }
}

