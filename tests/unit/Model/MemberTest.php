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
    public static function setUpBeforeClass(): void {
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
}
