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
    public function test()
    {

        require 'generated-conf/config.php';
        $user = User::create([
            'email' => 'example@example.com',
            'password' => '123456789',
        ]);
        $jwt = JWT::generateToken($user->getId(), $user->getSignOutCount());
        $controller = new ExampleController(new Data(['jwt' => $jwt]));
        $authenticator = $controller->getAuthenticator();
        $authenticator->check();
        $this->assertSame($user, $authenticator->getUser());
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
        $authenticator = new Authenticator();
        $authenticator->setJwt('example');
        $this->assertSame('example', $authenticator->getJwt());
    }
}
