<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Controller\SignOut;
use Abdyek\Whoo\Controller\SignUp;
use Abdyek\Whoo\Controller\SignIn;
use Abdyek\Whoo\Core\Config;
use Abdyek\Whoo\Core\Data;
use Abdyek\Whoo\Tool\JWT;
use Abdyek\Whoo\Exception\InvalidTokenException;

/**
 * @covers SignOut::
 */

class SignOutTest extends TestCase
{
    use Reset;

    public function setUp(): void
    {
        self::reset();
    }

    public function testRun()
    {
        $this->expectException(InvalidTokenException::class);
        $content = $this->getContent();

        $config = new Config();
        $config->setUseUsername(false);
        $config->setDenyIfNotVerifiedToSignIn(false);
        
        (new SignUp(new Data($content), $config))->triggerRun();

        $signIn = new SignIn(new Data($content), $config);
        $signIn->triggerRun();

        $responseContent = $signIn->getResponse()->getContent();
        $jwt = $responseContent['jwt'];
        
        ($signOut = new SignOut(new Data([
            'jwt' => $jwt,
        ]), $config))->triggerRun();

        $signOut->getAuthenticator()->check();
    }

    private function getContent(): array
    {
        return [
            'email' => 'example@example.com',
            'password' => 'this is password'
        ];
    }
}
