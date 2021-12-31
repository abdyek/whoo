<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Controller\SignUp;
use Abdyek\Whoo\Controller\SignInByUsername;
use Abdyek\Whoo\Core\Config;
use Abdyek\Whoo\Core\Data;
use Abdyek\Whoo\Repository\User;
use Abdyek\Whoo\Repository\AuthenticationCode;
use Abdyek\Whoo\Exception\IncorrectPasswordException;
use Abdyek\Whoo\Exception\NotFoundException;
use Abdyek\Whoo\Exception\NotVerifiedEmailException;
use Abdyek\Whoo\Exception\TwoFactorAuthEnabledException;
use Abdyek\Whoo\Exception\UnmatchedPasswordsException;
use Abdyek\Whoo\Config\Authentication as AuthConfig;
use Abdyek\Whoo\Tool\JWT;

/**
 * @covers SignInByUsername::
 */

class SignInByUsernameTest extends TestCase
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
        $config->setDefault2fa(false);
        $config->setDenyIfNotVerifiedToSignIn(false);
        $config->setUseUsername(true);

        (new SignUp(new Data($content), $config))->triggerRun();

        $signIn = new SignInByUsername(new Data($content), $config);
        $signIn->triggerRun();

        $responseContent = $signIn->getResponse()->getContent();
        $jwt = $responseContent['jwt'];
        $user = $responseContent['user'];

        $payload = (array) JWT::getPayloadWithUser($jwt)['payload'];

        $this->assertSame($user->getId(), $payload['whoo']->userId);
    }

    public function testRunIncorrectPasswordException()
    {
        $this->expectException(IncorrectPasswordException::class);
        $content = $this->getContent();

        $config = new Config();
        $config->setDefault2fa(false);
        $config->setDenyIfNotVerifiedToSignIn(false);
        $config->setUseUsername(true);

        (new SignUp(new Data($content), $config))->triggerRun();

        $content['password'] = 'wrong-' . $content['password'];

        (new SignInByUsername(new Data($content), $config))->triggerRun();
    }

    public function testRunNotFoundException()
    {
        $this->expectException(NotFoundException::class);
        $content = $this->getContent();

        (new SignInByUsername(new Data($content)))->triggerRun();
    }

    public function testRunNotVerifiedEmailException()
    {
        $this->expectException(NotVerifiedEmailException::class);
        $content = $this->getContent();

        $config = new Config();
        $config->setDefault2fa(false);
        $config->setDenyIfNotVerifiedToSignIn(true);
        $config->setUseUsername(true);

        (new SignUp(new Data($content), $config))->triggerRun();

        (new SignInByUsername(new Data($content), $config))->triggerRun();
    }

    public function testRunAuthCode()
    {
        $content = $this->getContent();

        $config = new Config();
        $config->setDefault2fa(false);
        $config->setDenyIfNotVerifiedToSignIn(true);
        $config->setUseUsername(true);

        (new SignUp(new Data($content), $config))->triggerRun();
        $user = User::getByEmail($content['email']);

        try {
            (new SignInByUsername(new Data($content), $config))->triggerRun();
        } catch (NotVerifiedEmailException $e) {
            $authCode = AuthenticationCode::getByUserIdType($user->getId(), AuthConfig::TYPE_EMAIL_VERIFICATION);
            $this->assertSame($authCode->getCode(), $e->authCode);
        }
    }

    public function testRun2FAWithException()
    {
        $this->expectException(TwoFactorAuthEnabledException::class);
        $content = $this->getContent();

        $config = new Config();
        $config->setDefault2fa(true);
        $config->setDenyIfNotVerifiedToSignIn(false);
        $config->setUseUsername(true);

        (new SignUp(new Data($content), $config))->triggerRun();

        (new SignInByUsername(new Data($content), $config))->triggerRun();
    }

    public function testRun2FA()
    {
        $content = $this->getContent();

        $config = new Config();
        $config->setDefault2fa(true);
        $config->setDenyIfNotVerifiedToSignIn(false);
        $config->setUseUsername(true);

        (new SignUp(new Data($content), $config))->triggerRun();

        try {
            (new SignInByUsername(new Data($content), $config))->triggerRun();
        } catch(TwoFactorAuthEnabledException $e) {
            $user = User::getByUsername($content['username']);
            $authCode = AuthenticationCode::getByUserIdType($user->getId(), AuthConfig::TYPE_2FA);
            $this->assertSame($authCode->getCode(), $e->authCode);
        }
    }

    public function testRunOptionalPasswordAgain()
    {
        $content = $this->getContent();

        $config = new Config();
        $config->setDefault2fa(false);
        $config->setDenyIfNotVerifiedToSignIn(false);
        $config->setUseUsername(true);

        (new SignUp(new Data($content), $config))->triggerRun();

        $content['passwordAgain'] = $content['password'];

        ($signIn = new SignInByUsername(new Data($content), $config))->triggerRun();

        $responseContent = $signIn->getResponse()->getContent();
        $jwt = $responseContent['jwt'];
        $user = $responseContent['user'];

        $payload = (array) JWT::getPayloadWithUser($jwt)['payload'];

        $this->assertSame($user->getId(), $payload['whoo']->userId);
    }

    public function testRunUnmatchedPasswordsException()
    {
        $this->expectException(UnmatchedPasswordsException::class);
        $content = $this->getContent();

        $config = new Config();
        $config->setDefault2fa(false);
        $config->setDenyIfNotVerifiedToSignIn(false);
        $config->setUseUsername(true);

        (new SignUp(new Data($content), $config))->triggerRun();

        $content['passwordAgain'] = 'wrong-' . $content['password'];

        ($signIn = new SignInByUsername(new Data($content), $config))->triggerRun();
    }

    private function getContent(): array
    {
        return [
            'email' => 'email@example.com',
            'password' => 'this is password',
            'username' => 'uS3rN@mE',
        ];
    }
}
