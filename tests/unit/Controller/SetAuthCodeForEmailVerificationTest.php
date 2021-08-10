<?php

require 'propel/config.php';
use PHPUnit\Framework\TestCase;
use Whoo\Controller\SetAuthCodeForEmailVerification;
use Whoo\Config\Authentication as AuthConfig;
use Whoo\Exception\NotFoundException;

/**
 * @covers SetAuthCodeForEmailVerification::
 */

class SetAuthCodeForEmailVerificationTest extends TestCase {
    use Reset;
    use UserTool;
    public function setUp(): void {
        self::reset();
    }
    public function testRunNotFoundException() {
        $this->expectException(NotFoundException::class);
        new SetAuthCodeForEmailVerification([
            'email'=>'notFound@notFound.com'
        ]);
    }
    public function testRun() {
        $user = $this->createExample();
        $setAuth = new SetAuthCodeForEmailVerification([
            'email'=>self::$traitEmail
        ]);
        $this->assertIsString($setAuth->code);
        $this->assertEquals(AuthConfig::SIZE_OF_CODE_FOR_EMAIL_VER, strlen($setAuth->code));
    }
}
