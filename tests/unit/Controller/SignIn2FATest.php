<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Controller\SignIn2FA;
use Abdyek\Whoo\Controller\SignUp;
use Abdyek\Whoo\Controller\SignIn;
use Abdyek\Whoo\Core\Config;
use Abdyek\Whoo\Core\Data;
use Abdyek\Whoo\Config\Authentication as AuthConfig;
use Abdyek\Whoo\Exception\NotFoundException;
use Abdyek\Whoo\Exception\NotFoundAuthCodeException;
use Abdyek\Whoo\Exception\TimeOutCodeException;
use Abdyek\Whoo\Exception\TrialCountOverException;
use Abdyek\Whoo\Exception\InvalidCodeException;
use Abdyek\Whoo\Exception\TwoFactorAuthEnabledException;
use Abdyek\Whoo\Repository\AuthenticationCode;
use Abdyek\Whoo\Repository\User;

/**
 * @covers SignIn2FA::
 */

class SignIn2FATest extends TestCase
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
        $config->setDefault2fa(true);
        $config->setDenyIfNotVerifiedToSignIn(false);

        (new SignUp(new Data($content), $config))->triggerRun();

        try {
            (new SignIn(new Data($content), $config))->triggerRun();
        } catch(TwoFactorAuthEnabledException $e) {
            $contentTo2FA = [
                'email' => $content['email'],
                'authCode' => $e->authCode
            ];

            $signIn2FA = new SignIn2FA(new Data($contentTo2FA), $config);
            $signIn2FA->triggerRun();

            $response = $signIn2FA->getResponse();
            $responseContent = $response->getContent();
            $this->assertNotNull($responseContent['jwt']);
        }
    }

    public function testRunNotFoundException()
    {
        $this->expectException(NotFoundException::class);

        $content = [
            'email' => 'nothing@example.com',
            'authCode' => 'nothing'
        ];

        (new SignIn2FA(new Data($content)))->triggerRun();
    }

    public function testRunNotFoundAuthCodeException()
    {
        $this->expectException(NotFoundAuthCodeException::class);
        $content = $this->getContent();

        $config = new Config();
        $config->setDenyIfNotVerifiedToSignIn(false);
        $config->setUseUsername(false);
        $config->setDefault2fa(true);
        
        (new SignUp(new Data($content), $config))->triggerRun();

        $contentTo2FA = [
            'email' => $content['email'],
            'authCode' => 'nothing'
        ];

        (new SignIn2FA(new Data($contentTo2FA), $config))->triggerRun();
    }

    public function testRunTrialCountOverException()
    {
        $this->expectException(TrialCountOverException::class);
        $content = $this->getContent();

        $config = new Config();
        $config->setDenyIfNotVerifiedToSignIn(false);
        $config->setUseUsername(false);
        $config->setDefault2fa(true);

        (new SignUp(new Data($content), $config))->triggerRun();

        try {
            (new SignIn(new Data($content), $config))->triggerRun();
        } catch(TwoFactorAuthEnabledException $e) {
            $contentTo2FA = [
                'email' => $content['email'],
                'authCode' => $e->authCode . 'wrong'
            ];
            for($i = 0; $i < $config->getTrialMaxCountToSignIn2fa(); $i++) {
                try {
                    (new SignIn2FA(new Data($contentTo2FA), $config))->triggerRun();
                } catch(InvalidCodeException $e) { }
            }
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
        $config->setDenyIfNotVerifiedToSignIn(false);
        $config->setUseUsername(false);
        $config->setDefault2fa(true);
        $config->setValidityTimeToSignIn2fa($validityTime);

        (new SignUp(new Data($content), $config))->triggerRun();

        try {
            (new SignIn(new Data($content), $config))->triggerRun();
        } catch(TwoFactorAuthEnabledException $e) {
            $contentTo2FA = [
                'email' => $content['email'],
                'authCode' => $e->authCode,
            ];
            $user = User::getByEmail($content['email']);
            $auth = AuthenticationCode::getByUserIdType($user->getId(), AuthConfig::TYPE_2FA);
            $timestamp = $auth->getDateTime()->getTimestamp();

            $dateTime = new \DateTime();
            $dateTime->setTimestamp($timestamp + $requestTime);

            $signIn2FA = new SignIn2FA(new Data($contentTo2FA), $config, null, $dateTime);
            $signIn2FA->triggerRun();

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
            'email'=>'example@email.com',
            'password'=>'this_is_pw'
        ];
    }

}
