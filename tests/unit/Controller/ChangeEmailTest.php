<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Controller\SignUp;
use Abdyek\Whoo\Controller\SignIn;
use Abdyek\Whoo\Controller\ChangeEmail;
use Abdyek\Whoo\Core\Config;
use Abdyek\Whoo\Core\Data;
use Abdyek\Whoo\Tool\JWT;
use Abdyek\Whoo\Repository\User;
use Abdyek\Whoo\Exception\IncorrectPasswordException;
use Abdyek\Whoo\Exception\NotUniqueEmailException;
use Abdyek\Whoo\Exception\InvalidTokenException;

/**
 * @covers ChangeEmail::
 */

class ChangeEmailTest extends TestCase
{
    use Reset;

    public function setUp(): void
    {
        self::reset();
    }

    public function testRun()
    {
        $content = $this->getContent();
        $newEmail = 'new@example.com';

        $config = new Config();
        $config->setUseUsername(false);
        $config->setDenyIfNotVerifiedToSignIn(false);

        (new SignUp(new Data($content), $config))->triggerRun();

        ($signIn = new SignIn(new Data($content), $config))->triggerRun();

        $userId = $signIn->getResponse()->getContent()['user']->getId();

        (new ChangeEmail(new Data([
            'jwt' => $signIn->getResponse()->getContent()['jwt'],
            'newEmail' => $newEmail,
            'password' => $content['password']
        ]), $config))->triggerRun();

        $this->assertEquals($userId, User::getByEmail($newEmail)->getId());
    }

    public function testRunIncorrectPasswordException()
    {
        $this->expectException(IncorrectPasswordException::class);
        $content = $this->getContent();

        $config = new Config();
        $config->setUseUsername(false);
        $config->setDenyIfNotVerifiedToSignIn(false);
        $config->setDefault2fa(false);

        (new SignUp(new Data($content), $config))->triggerRun();

        ($signIn = new SignIn(new Data($content), $config))->triggerRun();

        (new ChangeEmail(new Data([
            'jwt' => $signIn->getResponse()->getContent()['jwt'],
            'newEmail' => 'newEmail@example.com',
            'password' => 'wrong-' . $content['password'],
        ]), $config))->triggerRun();
    }

    public function testRunNotUniqueEmailException()
    {
        $this->expectException(NotUniqueEmailException::class);
        $content = $this->getContent();

        $anotherUser = [
            'email' => 'unique@example.com',
            'password' => $content['password'],
        ];

        $config = new Config();
        $config->setUseUsername(false);
        $config->setDenyIfNotVerifiedToSignIn(false);
        $config->setDefault2fa(false);

        (new SignUp(new Data($content), $config))->triggerRun();

        (new SignUp(new Data($anotherUser), $config))->triggerRun();

        ($signIn = new SignIn(new Data($content), $config))->triggerRun();

        (new ChangeEmail(new Data([
            'jwt' => $signIn->getResponse()->getContent()['jwt'],
            'newEmail' => $anotherUser['email'],
            'password' => $content['password'],
        ]), $config))->triggerRun();
    }

    public function testRunSignOut()
    {
        $this->expectException(InvalidTokenException::class);
        $content = $this->getContent();

        $config = new Config();
        $config->setUseUsername(false);
        $config->setDenyIfNotVerifiedToSignIn(false);
        $config->setDefault2fa(false);

        (new SignUp(new Data($content), $config))->triggerRun();

        ($signIn = new SignIn(new Data($content), $config))->triggerRun();

        (new ChangeEmail(new Data([
            'jwt' => $signIn->getResponse()->getContent()['jwt'],
            'newEmail' => 'newEmail@example.com',
            'password' => $content['password'],
        ]), $config))->triggerRun();

        JWT::getPayloadWithUser($signIn->getResponse()->getContent()['jwt']);
    }

    private function getContent(): array
    {
        return [
            'email'=>'email@example.com',
            'password'=>'this_is_password'
        ];
    }
}
