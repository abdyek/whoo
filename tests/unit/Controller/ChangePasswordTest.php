<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Controller\ChangePassword;
use Abdyek\Whoo\Controller\SignUp;
use Abdyek\Whoo\Controller\SignIn;
use Abdyek\Whoo\Core\Config;
use Abdyek\Whoo\Core\Data;
use Abdyek\Whoo\Exception\IncorrectPasswordException;
use Abdyek\Whoo\Repository\User;

/**
 * @covers ChangePassword::
 */

class ChangePasswordTest extends TestCase
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
        $config->setUseUsername(false);
        $config->setDefault2fa(false);

        (new SignUp(new Data($content), $config))->triggerRun();

        ($signIn = new SignIn(new Data($content), $config))->triggerRun();

        (new ChangePassword(new Data([
            'jwt' => $signIn->getResponse()->getContent()['jwt'],
            'password' => $content['password'],
            'newPassword' => 'new' . $content['password'],
        ]), $config))->triggerRun();

        $this->assertTrue(User::checkPassword($signIn->getResponse()->getContent()['user'], 'new' . $content['password']));
    }

    public function testRunIncorrectPasswordException()
    {
        $this->expectException(IncorrectPasswordException::class);
        $content = $this->getContent();

        $config = new Config();
        $config->setDenyIfNotVerifiedToSignIn(false);
        $config->setUseUsername(false);
        $config->setDefault2fa(false);

        (new SignUp(new Data($content), $config))->triggerRun();

        ($signIn = new SignIn(new Data($content), $config))->triggerRun();

        (new ChangePassword(new Data([
            'jwt' => $signIn->getResponse()->getContent()['jwt'],
            'password' => 'wrong-' . $content['password'],
            'newPassword' => 'new' . $content['password'],
        ]), $config))->triggerRun();
    }

    private function getContent(): array
    {
        return [
            'email'=>'emailExample@email.com',
            'password'=>'this_is_password'
        ];
    }
}
        
