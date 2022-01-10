<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Controller\SignInByUsername2FA;
use Abdyek\Whoo\Controller\SignUp;
use Abdyek\Whoo\Controller\SetUsername;
use Abdyek\Whoo\Controller\SignInByUsername;
use Abdyek\Whoo\Core\Config;
use Abdyek\Whoo\Core\Data;
use Abdyek\Whoo\Exception\TwoFactorAuthEnabledException;
use Abdyek\Whoo\Exception\TimeOutCodeException;
use Abdyek\Whoo\Exception\NotFoundException;
use Abdyek\Whoo\Exception\NotFoundAuthCodeException;
use Abdyek\Whoo\Exception\TrialCountOverException;
use Abdyek\Whoo\Exception\InvalidCodeException;
use Abdyek\Whoo\Config\Authentication as AuthConfig;
use Abdyek\Whoo\Repository\User;
use Abdyek\Whoo\Repository\AuthenticationCode;
use Abdyek\Whoo\Tool\JWT;

/**
 * @covers SignInByUsername2FA::
 */

class SignInByUsername2FATest extends TestCase
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
        $config->setUseUsername(true);
        $config->setDefault2fa(true);
        $config->setDenyIfNotVerifiedToSignIn(false);

        (new SignUp(new Data($content), $config))->triggerRun();

        try {
            (new SignInByUsername(new Data($content), $config))->triggerRun();
        } catch(TwoFactorAuthEnabledException $e) {
            ($signIn2FA = new SignInByUsername2FA(new Data([
                'username' => $content['username'],
                'authCode' => $e->authCode,
            ]), $config))->triggerRun();

            $responseContent = $signIn2FA->getResponse()->getContent();
            $jwt = $responseContent['jwt'];
            $user = $responseContent['user'];
        }
        $jwtObject = new JWT;
        $payload = $jwtObject->payload($jwt);

        $this->assertEquals($user->getId(), $payload['whoo']->userId);
    }

    public function testRunInvalidCodeException()
    {
        $this->expectException(InvalidCodeException::class);
        $content = $this->getContent();

        $config = new Config();
        $config->setUseUsername(true);
        $config->setDefault2fa(true);
        $config->setDenyIfNotVerifiedToSignIn(false);

        (new SignUp(new Data($content), $config))->triggerRun();

        try {
            (new SignInByUsername(new Data($content), $config))->triggerRun();
        } catch(TwoFactorAuthEnabledException $e) {
            (new SignInByUsername2FA(new Data([
                'username' => $content['username'],
                'authCode' => 'wrong' . $e->authCode,
            ]), $config))->triggerRun();
        }
    }

    public function testRunNotFoundException()
    {
        $this->expectException(NotFoundException::class);
        (new SignInByUsername2FA(new Data([
            'username'=>'nothing',
            'authCode'=>'12345'
        ])))->triggerRun();
    }
    
    public function testRunNotFoundAuthCodeException()
    {
        $this->expectException(NotFoundAuthCodeException::class);
        $content = $this->getContent();

        $config = new Config();
        $config->setUseUsername(true);
        $config->setDefault2fa(true);
        $config->setDenyIfNotVerifiedToSignIn(false);

        (new SignUp(new Data($content), $config))->triggerRun();

        (new SignInByUsername2FA(new Data([
            'username' => $content['username'],
            'authCode' => 'wrong',
        ]), $config))->triggerRun();
    }

    public function testRunTrialCountOverException()
    {
        $this->expectException(TrialCountOverException::class);

        $content = $this->getContent();

        $config = new Config();
        $config->setUseUsername(true);
        $config->setDefault2fa(true);
        $config->setDenyIfNotVerifiedToSignIn(false);

        (new SignUp(new Data($content), $config))->triggerRun();

        try {
            (new SignInByUsername(new Data($content), $config))->triggerRun();
        } catch(TwoFactorAuthEnabledException $e) {
            for($i = 0; $i < $config->getTrialMaxCountToSignIn2fa(); $i++) {
                try {
                    (new SignInByUsername2FA(new Data([
                        'username' => $content['username'],
                        'authCode' => 'wrong',
                    ]), $config))->triggerRun();
                } catch(InvalidCodeException $e) { }
            }
        }
    }

    /**
     * @dataProvider providerForRunTimeOutCode
     */
    public function testRunTimeOutCodeException(int $validityTime, int $requestTime, bool $exception) {
        if($exception) {
            $this->expectException(TimeOutCodeException::class);
        }
        $content = $this->getContent();

        $config = new Config();
        $config->setDenyIfNotVerifiedToSignIn(false);
        $config->setUseUsername(true);
        $config->setDefault2fa(true);
        $config->setValidityTimeToSignIn2fa($validityTime);

        (new SignUp(new Data($content), $config))->triggerRun();

        try {
            (new SignInByUsername(new Data($content), $config))->triggerRun();
        } catch(TwoFactorAuthEnabledException $e) {
            $user = User::getByEmail($content['email']);
            $auth = AuthenticationCode::getByUserIdType($user->getId(), AuthConfig::TYPE_2FA);
            $timestamp = $auth->getDateTime()->getTimestamp();

            $dateTime = new \DateTime();
            $dateTime->setTimestamp($timestamp + $requestTime);

            ($signIn2FA = new SignInByUsername2FA(new Data([
                'username' => $content['username'],
                'authCode' => $e->authCode,
            ]), $config, null, $dateTime))->triggerRun();

            if(!$exception) {
                $responseContent = $signIn2FA->getResponse()->getContent();
                $user = $responseContent['user'];
                $this->assertSame($content['email'], $user->getEmail());
            }
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

    private function getContent(): array
    {
        return [
            'email' => 'example@example.com',
            'password' => 'this_is_password',
            'username' => 'userName',
        ];
    }
}
