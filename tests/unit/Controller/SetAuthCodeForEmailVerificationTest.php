<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Controller\SignUp;
use Abdyek\Whoo\Controller\SetAuthCodeForEmailVerification;
use Abdyek\Whoo\Config\Authentication as AuthConfig;
use Abdyek\Whoo\Exception\NotFoundException;
use Abdyek\Whoo\Core\Config;
use Abdyek\Whoo\Core\Data;
use Abdyek\Whoo\Repository\User;
use Abdyek\Whoo\Repository\AuthenticationCode;

/**
 * @covers SetAuthCodeForEmailVerification::
 */

class SetAuthCodeForEmailVerificationTest extends TestCase
{
    use Reset;

    public function setUp(): void
    {
        self::reset();
    }

    public function testRunNotFoundException()
    {
        $this->expectException(NotFoundException::class);
        (new SetAuthCodeForEmailVerification(new Data([
            'email' => 'notFound@example.com'
        ])))->triggerRun();
    }

    public function testRun()
    {
        $content = $this->getContent();

        $config = new Config();
        $config->setUseUsername(false);
        $config->setDefault2fa(false);

        $signUp = new SignUp(new Data($content), $config);
        $signUp->triggerRun();

        $user = User::getByEmail($content['email']);

        $setAuth = new SetAuthCodeForEmailVerification(new Data([
            'email' => $content['email']
        ]), $config);
        $setAuth->triggerRun();

        $authCode = AuthenticationCode::getByUserIdType($user->getId(), AuthConfig::TYPE_EMAIL_VERIFICATION);

        $this->assertSame($authCode->getCode(), $setAuth->getResponse()->getContent()['authCode']);
    }

    private function getContent(): array
    {
        return [
            'email' => 'example@example.com',
            'password' => '12341234'
        ];
    }

}
