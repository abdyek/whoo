<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Controller\SignUp;
use Abdyek\Whoo\Controller\SetAuthCodeToResetPassword;
use Abdyek\Whoo\Config\Authentication as AuthConfig;
use Abdyek\Whoo\Exception\NotFoundException;
use Abdyek\Whoo\Exception\NotVerifiedEmailException;
use Abdyek\Whoo\Core\Config;
use Abdyek\Whoo\Core\Data;
use Abdyek\Whoo\Repository\AuthenticationCode;
use Abdyek\Whoo\Repository\User;

/**
 * @covers SetAuthCodeToResetPassword::
 */

class SetAuthCodeToResetPasswordTest extends TestCase
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
        $config->setDenyIfNotVerifiedToResetPw(false);
        $config->setDefault2fa(false);

        (new SignUp(new Data($content), $config))->triggerRun();

        ($setAuth = new SetAuthCodeToResetPassword(new Data([
            'email' => $content['email']
        ]), $config))->triggerRun();

        $user = User::getByEmail($content['email']);

        $authCode = AuthenticationCode::getByUserIdType($user->getId(), AuthConfig::TYPE_RESET_PW);
    
        $this->assertSame($authCode->getCode(), $setAuth->getResponse()->getContent()['authCode']);
    }

    public function testRunNotFoundException()
    {
        $this->expectException(NotFoundException::class);

        (new SetAuthCodeToResetPassword(new Data([
            'email' => 'nothing@example.com',
        ])))->triggerRun();
    }

    public function testRunDenyIfNotVerifiedToResetPWTrue()
    {
        $this->expectException(NotVerifiedEmailException::class);
        $content = $this->getContent();

        $config = new Config();
        $config->setUseUsername(false);
        $config->setDenyIfNotVerifiedToResetPw(true);
        $config->setDefault2fa(false);

        (new SignUp(new Data($content), $config))->triggerRun();

        (new SetAuthCodeToResetPassword(new Data([
            'email' => $content['email'],
        ])))->triggerRun();
    }

    private function getContent(): array
    {
        return [
            'email'=>'thisIsEmail@test.com',
            'password'=>'thisIsPassword'
        ];
    }
}
