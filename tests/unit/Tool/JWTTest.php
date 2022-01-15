<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Tool\JWT;
use Abdyek\Whoo\Exception\InvalidTokenException;

/**
 * @covers JWT::
 */

class JWTTest extends TestCase
{
    public function test()
    {
        $jwtObject = new JWT;
        $claims = [
            'iss' => 'http://example.org',
            'aud' => 'http://example.com',
            'iat' => 1356999524,
            'nbf' => 1357000000,
        ];
        $jwtObject->setClaims($claims);
        $jwt = $jwtObject->generateToken(12, 0);
        $payload = $jwtObject->payload($jwt);
        $this->assertEquals(12, $payload['whoo']['userId']);
    }

    public function testInvalidTokenException()
    {
        $this->expectException(InvalidTokenException::class);
        $jwtObject = new JWT;
        $jwtObject->payload('invalid');
    }
}
