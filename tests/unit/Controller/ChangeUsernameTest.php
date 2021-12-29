<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Controller\ChangeUsername;
use Abdyek\Whoo\Controller\SignUp;
use Abdyek\Whoo\Controller\SignIn;
use Abdyek\Whoo\Controller\SetUsername;
use Abdyek\Whoo\Core\Config;
use Abdyek\Whoo\Core\Data;
use Abdyek\Whoo\Exception\NotUniqueUsernameException;

/**
 * @covers ChangeUsername::
 */

class ChangeUsernameTest extends TestCase
{
    use Reset;

    public function setUp(): void
    {
        self::reset();
    }

    public function testRun()
    {
        $content = $this->getContent();

        $config = new Config();
        $config->setDenyIfNotVerifiedToSignIn(false);
        $config->setUseUsername(true);
        $config->setDefault2fa(false);

        (new SignUp(new Data($content), $config))->triggerRun();

        ($signIn = new SignIn(new Data($content), $config))->triggerRun();

        (new ChangeUsername(new Data([
            'jwt' => $signIn->getResponse()->getContent()['jwt'],
            'newUsername' => 'new' . $content['username'],
        ])))->triggerRun();

        $this->assertSame('new' . $content['username'], $signIn->getResponse()->getContent()['user']->getUsername());
    }

    public function testRunNotUniqueUsernameException()
    {
        $this->expectException(NotUniqueUsernameException::class);

        $content = $this->getContent();

        $config = new Config();
        $config->setDenyIfNotVerifiedToSignIn(false);
        $config->setUseUsername(true);
        $config->setDefault2fa(false);

        (new SignUp(new Data($content), $config))->triggerRun();

        $anotherUser = [
            'email' => 'another@example.com',
            'password' => $content['password'],
            'username' => 'anotherUser'
        ];

        (new SignUp(new Data($anotherUser), $config))->triggerRun();

        ($signIn = new SignIn(new Data($content), $config))->triggerRun();

        (new ChangeUsername(new Data([
            'jwt' => $signIn->getResponse()->getContent()['jwt'],
            'newUsername' => $anotherUser['username'],
        ])))->triggerRun();
    }

    private function getContent(): array
    {
        return [
            'email' => 'email@example.com',
            'password' => 'this_is_password',
            'username' => 'userName',
        ];
    }
}
