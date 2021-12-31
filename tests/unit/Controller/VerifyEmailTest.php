<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Controller\VerifyEmail;
use Abdyek\Whoo\Controller\SignUp;
use Abdyek\Whoo\Controller\SetAuthCodeForEmailVerification;
use Abdyek\Whoo\Core\Config;
use Abdyek\Whoo\Core\Data;
use Abdyek\Whoo\Exception\InvalidCodeException;
use Abdyek\Whoo\Exception\TimeOutCodeException;
use Abdyek\Whoo\Exception\TrialCountOverException;
use Abdyek\Whoo\Exception\NotFoundException;
use Abdyek\Whoo\Exception\NotFoundAuthCodeException;
use Abdyek\Whoo\Config\Authentication as AuthConfig;
use Abdyek\Whoo\Repository\AuthenticationCode;
use Abdyek\Whoo\Repository\User;
use Abdyek\Whoo\Config\Propel as PropelConfig;

/**
 * @covers VerifyEmail::
 */

class VerifyEmailTest extends TestCase
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

        ($setAuth = new SetAuthCodeForEmailVerification(new Data([
            'email' => $content['email'],
        ]), $config))->triggerRun();

        (new VerifyEmail(new Data([
            'email' => $content['email'],
            'authCode' => $setAuth->getResponse()->getContent()['authCode'],
        ]), $config))->triggerRun();

        $user = User::getByEmail($content['email']);

        $this->assertTrue($user->getEmailVerified());
    }

    public function testRunNotFoundExcepiton()
    {
        $this->expectException(NotFoundException::class);
        (new VerifyEmail(new Data([
            'email' => 'nothing@example.com',
            'authCode' => 'authCode',
        ])))->triggerRun();
    }

    public function testRunNotFoundAuthCodeException()
    {
        $this->expectException(NotFoundAuthCodeException::class);
        $content = $this->getContent();

        $config = new Config();
        $config->setDefault2fa(false);

        (new SignUp(new Data($content), $config))->triggerRun();

        (new VerifyEmail(new Data([
            'email' => $content['email'],
            'authCode' => 'authCode',
        ]), $config))->triggerRun();
    }

    public function testRunInvalidCodeException()
    {
        $this->expectException(InvalidCodeException::class);

        $content = $this->getContent();

        $config = new Config();
        $config->setDefault2fa(false);

        (new SignUp(new Data($content), $config))->triggerRun();

        (new SetAuthCodeForEmailVerification(new Data([
            'email' => $content['email'],
        ]), $config))->triggerRun();

        (new VerifyEmail(new Data([
            'email' => $content['email'],
            'authCode' => 'wrong',
        ]), $config))->triggerRun();
    }

    public function testRunTrialCountOverException()
    {
        $this->expectException(TrialCountOverException::class);

        $content = $this->getContent();

        $config = new Config();
        $config->setDefault2fa(false);

        (new SignUp(new Data($content), $config))->triggerRun();

        (new SetAuthCodeForEmailVerification(new Data([
            'email' => $content['email'],
        ]), $config))->triggerRun();

        for($i = 0; $i < $config->getTrialMaxCountToVerifyEmail(); $i++) {
            try {
                (new VerifyEmail(new Data([
                    'email' => $content['email'],
                    'authCode' => 'wrong',
                ]), $config))->triggerRun();
            } catch(InvalidCodeException $e) { }
        }
    }

    /**
     * @dataProvider providerForRunTimeOutCode
     */
    public function testRunTimeOutCodeException(int $validityTime, int $requestTime, bool $exception)
    {
        if($exception) {
            $this->expectException(TimeOutCodeException::class);
        }

        $content = $this->getContent();

        $config = new Config();
        $config->setDefault2fa(false);
        $config->setValidityTimeToVerifyEmail($validityTime);

        (new SignUp(new Data($content), $config))->triggerRun();

        ($setAuth = new SetAuthCodeForEmailVerification(new Data([
            'email' => $content['email'],
        ]), $config))->triggerRun();

        $user = User::getByEmail($content['email']);
        $auth = AuthenticationCode::getByUserIdType($user->getId(), AuthConfig::TYPE_EMAIL_VERIFICATION);
        $timestamp = $auth->getDateTime()->getTimestamp();

        $dateTime = new \DateTime();
        $dateTime->setTimestamp($timestamp + $requestTime);

        (new VerifyEmail(new Data([
            'email' => $content['email'],
            'authCode' => $setAuth->getResponse()->getContent()['authCode'],
        ]), $config, null, $dateTime))->triggerRun();

        if(!$exception) {
            $this->assertTrue($user->getEmailVerified());
        }
    }

    public function providerForRunTimeOutCode(): array
    {
        return [
            [
                120,
                120,
                false
            ],
            [
                120,
                110,
                false
            ],
            [
                120,
                130,
                true
            ],
            [
                120,
                121,
                true
            ]
        ];
    }

    public function getContent(): array
    {
        return [
            'email' => 'example@example.com',
            'password' => '123123123'
        ];
    }
}
