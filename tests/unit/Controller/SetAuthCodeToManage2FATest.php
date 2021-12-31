<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Controller\SetAuthCodeToManage2FA;
use Abdyek\Whoo\Controller\SignUp;
use Abdyek\Whoo\Controller\SignIn;
use Abdyek\Whoo\Core\Config;
use Abdyek\Whoo\Core\Data;
use Abdyek\Whoo\Config\Authentication as AuthConfig;
use Abdyek\Whoo\Exception\IncorrectPasswordException;
use Abdyek\Whoo\Repository\AuthenticationCode;
use Abdyek\Whoo\Repository\User;

/**
 * @covers SetAuthCodeToManage2FA::
 */

class SetAuthCodeToManage2FATest extends TestCase
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
        $config->setUseUsername(false);
        $config->setDenyIfNotVerifiedToSignIn(false);
        $config->setDefault2fa(false);

        (new SignUp(new Data($content), $config))->triggerRun();

        ($signIn = new SignIn(new Data($content), $config))->triggerRun();

        ($setAuth = new SetAuthCodeToManage2FA(new Data([
            'jwt' => $signIn->getResponse()->getContent()['jwt'],
            'password' => $content['password'],
        ]), $config))->triggerRun();

        $user = User::getByEmail($content['email']);

        $authCode = AuthenticationCode::getByUserIdType($user->getId(), AuthConfig::TYPE_MANAGE_2FA);
    
        $this->assertSame($authCode->getCode(), $setAuth->getResponse()->getContent()['authCode']);
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

        ($setAuth = new SetAuthCodeToManage2FA(new Data([
            'jwt' => $signIn->getResponse()->getContent()['jwt'],
            'password' => 'wrong-' . $content['password'],
        ]), $config))->triggerRun();
    }

    private function getContent(): array
    {
        return [
            'email'=>'this_is_email@email.com',
            'password'=>'this-is-password'
        ];
    }
}
