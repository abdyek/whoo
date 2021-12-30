<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Controller\Manage2FA;
use Abdyek\Whoo\Controller\SignUp;
use Abdyek\Whoo\Controller\SignIn;
use Abdyek\Whoo\Controller\SetAuthCodeToManage2FA;
use Abdyek\Whoo\Core\Config;
use Abdyek\Whoo\Core\Data;
use Abdyek\Whoo\Exception\NotFoundAuthCodeException;
use Abdyek\Whoo\Exception\InvalidCodeException;;
use Abdyek\Whoo\Exception\TimeOutCodeException;
use Abdyek\Whoo\Exception\TrialCountOverException;;
use Abdyek\Whoo\Config\Authentication as AuthConfig;
use Abdyek\Whoo\Model\User;

/**
 * @covers Manage2FA::
 */

class Manage2FATest extends TestCase
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

        $jwt = $signIn->getResponse()->getContent()['jwt'];

        ($auth = new SetAuthCodeToManage2FA(new Data([
            'jwt' => $jwt,
            'password' => $content['password']
        ]), $config))->triggerRun();

        (new Manage2FA(new Data([
            'jwt' => $jwt,
            'authCode' => $auth->getResponse()->getContent()['authCode'],
            'open' => true
        ]), $config))->triggerRun();

        $user = User::getByEmail($content['email']);

        $this->assertTrue($user->getTwoFactorAuthentication());
    }

    public function testRunInvalidCodeException()
    {
        $this->expectException(InvalidCodeException::class);
        $content = $this->getContent();

        $config = new Config();
        $config->setUseUsername(false);
        $config->setDenyIfNotVerifiedToSignIn(false);
        $config->setDefault2fa(false);

        (new SignUp(new Data($content), $config))->triggerRun();

        ($signIn = new SignIn(new Data($content), $config))->triggerRun();

        $jwt = $signIn->getResponse()->getContent()['jwt'];

        ($auth = new SetAuthCodeToManage2FA(new Data([
            'jwt' => $jwt,
            'password' => $content['password']
        ]), $config))->triggerRun();

        (new Manage2FA(new Data([
            'jwt' => $jwt,
            'authCode' => 'wrong-code',
            'open' => true
        ]), $config))->triggerRun();
    }

    public function testRunNotFoundAuthCodeException()
    {
        $this->expectException(NotFoundAuthCodeException::class);
        $content = $this->getContent();

        $config = new Config();
        $config->setUseUsername(false);
        $config->setDenyIfNotVerifiedToSignIn(false);
        $config->setDefault2fa(false);

        (new SignUp(new Data($content), $config))->triggerRun();

        ($signIn = new SignIn(new Data($content), $config))->triggerRun();

        $jwt = $signIn->getResponse()->getContent()['jwt'];

        (new Manage2FA(new Data([
            'jwt' => $jwt,
            'authCode' => 'code',
            'open' => true
        ]), $config))->triggerRun();
    }

    public function testRunTrialCountOverException()
    {
        $this->expectException(TrialCountOverException::class);
        $content = $this->getContent();

        $config = new Config();
        $config->setUseUsername(false);
        $config->setDenyIfNotVerifiedToSignIn(false);
        $config->setDefault2fa(false);

        (new SignUp(new Data($content), $config))->triggerRun();

        ($signIn = new SignIn(new Data($content), $config))->triggerRun();

        $jwt = $signIn->getResponse()->getContent()['jwt'];

        ($auth = new SetAuthCodeToManage2FA(new Data([
            'jwt' => $jwt,
            'password' => $content['password']
        ]), $config))->triggerRun();

        for($i = 0; $i < $config->getTrialMaxCountToManage2fa(); $i++) {
            try {
                (new Manage2FA(new Data([
                    'jwt' => $jwt,
                    'authCode' => 'wrong-code',
                    'open' => true
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
        $config->setDenyIfNotVerifiedToSignIn(false);
        $config->setUseUsername(false);
        $config->setDefault2fa(false);
        $config->setValidityTimeToManage2fa($validityTime);

        (new SignUp(new Data($content), $config))->triggerRun();

        ($signIn = new SignIn(new Data($content), $config))->triggerRun();

        $jwt = $signIn->getResponse()->getContent()['jwt'];
        
        ($auth = new SetAuthCodeToManage2FA(new Data([
            'jwt' => $jwt,
            'password' => $content['password']
        ]), $config))->triggerRun();

        $dateTime = new \DateTime();
        $dateTime->setTimestamp($auth->getDateTime()->getTimestamp() + $requestTime);

        (new Manage2FA(new Data([
            'jwt' => $jwt,
            'authCode' => $auth->getResponse()->getContent()['authCode'],
            'open' => true
        ]), $config, null, $dateTime))->triggerRun();

        if(!$exception) {
            $user = User::getByEmail($content['email']);
            $this->assertTrue($user->getTwoFactorAuthentication());
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
            'email' => 'email@email.com',
            'password' => 'this_is_password',
        ];
    }
}
