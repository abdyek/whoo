<?php

use PHPUnit\Framework\TestCase;
use Whoo\Tool\Random;

/**
 * @covers Random::
 */

class RandomTest extends TestCase {
    public function testNumber() {
        for($size=1; $size<20; $size++) {
            $randomNumber = Random::number($size);
            $this->assertEquals($size, strlen($randomNumber));
            $this->assertIsNumeric($randomNumber);
        }
    }
    public function testChars() {
        for($size=1; $size<20; $size++) {
            $randomChars = Random::chars($size);
            $this->assertEquals($size, strlen($randomChars));
            $this->assertIsString($randomChars);
        }
    }
}
