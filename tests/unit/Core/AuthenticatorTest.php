<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Core\Authenticator;
use Abdyek\Whoo\Core\Data;
use Abdyek\Whoo\Tool\JWT;
use Abdyek\Whoo\Repository\User;
use Abdyek\Whoo\Exception\InvalidTokenException;
use Pseudo\ExampleController;

/**
 * @covers Authenticator::
 */

class AuthenticatorTest extends TestCase
{
    private const SECRET_KEY = 'example_secret_key';
    private const JWT_ALGORITHM = 'HS512';

    public function testCheck()
    {
        require 'generated-conf/config.php';
        $user = User::create([
            'email' => 'example@example.com',
            'password' => '123456789',
        ]);
        $controller = new ExampleController(new Data);
        $controller->getConfig()->setSecretKey(self::SECRET_KEY);
        $controller->getConfig()->setJWTAlgorithm(self::JWT_ALGORITHM);

        $jwtObject = new JWT;
        $jwtObject->setSecretKey(self::SECRET_KEY);
        $jwtObject->setAlgorithm(self::JWT_ALGORITHM);
        $jwt = $jwtObject->generateToken($user->getId(), $user->getSignOutCount());
        
        $controller->getData()->setContent([
            'jwt' => $jwt
        ]);

        $authenticator = $controller->getAuthenticator();
        $authenticator->check();

        $this->assertSame($user, $authenticator->getUser());
        $this->assertSame(self::SECRET_KEY, $authenticator->getJWTObject()->getSecretKey());
        $this->assertSame(self::JWT_ALGORITHM, $authenticator->getJWTObject()->getAlgorithm());
    }

    public function testInvalidaTokenException()
    {
        $this->expectException(InvalidTokenException::class);
        $controller = new ExampleController(new Data(['jwt' => 'invalid token']));
        $authenticator = $controller->getAuthenticator();
        $authenticator->check();
    }

    public function testGetSetJwt()
    {
        $authenticator = new Authenticator(new JWT);
        $authenticator->setJwt('example');
        $this->assertSame('example', $authenticator->getJwt());
    }
}
