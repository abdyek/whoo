<?php

require 'propel/config.php';
use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Tool\JWT;
use Abdyek\Whoo\Exception\InvalidTokenException;

/**
 * @covers JWT::
 */

class JWTTest extends TestCase {
    use Reset;
    use UserTool;
    public function testGenerateToken() {
        $user = $this->createExample();
        $jwt = JWT::generateToken($user->getId(), $user->getSignOutCount());
        $payload = JWT::getPayloadWithUser($jwt)['payload'];
        $this->assertEquals($user->getId(), $payload['whoo']->userId);
    }
    public function testGetPayloadInvalidTokenException() {
        $this->expectException(InvalidTokenException::class);
        JWT::getPayloadWithUser('invalid-token');
    }
    public function setGetSecretKey() {
        $newSecret = 'new-secret-key';
        JWT::setSecretKey($newSecret);
        $this->assertEquals($newSecret, JWT::getSecretKey());
    }
}
