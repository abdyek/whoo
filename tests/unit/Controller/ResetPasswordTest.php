<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Controller\ResetPassword;
use Abdyek\Whoo\Controller\SignUp;
use Abdyek\Whoo\Controller\SignIn;
use Abdyek\Whoo\Controller\SetAuthCodeToResetPassword;
use Abdyek\Whoo\Core\Config;
use Abdyek\Whoo\Core\Data;
use Abdyek\Whoo\Exception\InvalidCodeException;
use Abdyek\Whoo\Exception\NotFoundException;
use Abdyek\Whoo\Exception\NotFoundAuthCodeException;
use Abdyek\Whoo\Exception\NotVerifiedEmailException;
use Abdyek\Whoo\Exception\TimeOutCodeException;
use Abdyek\Whoo\Exception\TrialCountOverException;
use Abdyek\Whoo\Config\Authentication as AuthConfig;
use Abdyek\Whoo\Model\AuthenticationCode;
use Abdyek\Whoo\Model\User;

/**
 * @covers ResetPassword::
 */

class ResetPasswordTest extends TestCase
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
        $config->setDenyIfNotVerifiedToResetPw(false);

        (new SignUp(new Data($content), $config))->triggerRun();

        ($setAuth = new SetAuthCodeToResetPassword(new Data([
            'email'=>$content['email']
        ]), $config))->triggerRun();

        (new ResetPassword(new Data([
            'email' => $content['email'],
            'newPassword' => 'new' . $content['password'],
            'authCode' => $setAuth->getResponse()->getContent()['authCode'],
        ]), $config))->triggerRun();

        $user = User::getByEmail($content['email']);

        $this->assertTrue(User::checkPassword($user, 'new'. $content['password']));
    }

    public function testRunNotFoundException()
    {
        $this->expectException(NotFoundException::class);

        (new ResetPassword(new Data([
            'email' => 'nothing@example.com',
            'newPassword' => 'password',
            'authCode' => 'authCode',
        ])))->triggerRun();
    }

    public function testRunInvalidCodeException()
    {
        $this->expectException(InvalidCodeException::class);
        $content = $this->getContent();

        $config = new Config();
        $config->setUseUsername(false);
        $config->setDenyIfNotVerifiedToSignIn(false);
        $config->setDenyIfNotVerifiedToResetPw(false);

        (new SignUp(new Data($content), $config))->triggerRun();

        ($setAuth = new SetAuthCodeToResetPassword(new Data([
            'email'=>$content['email']
        ]), $config))->triggerRun();

        (new ResetPassword(new Data([
            'email' => $content['email'],
            'newPassword' => 'new' . $content['password'],
            'authCode' => 'wrong',
        ]), $config))->triggerRun();
    }

    public function testRunNotFoundAuthCodeException()
    {
        $this->expectException(NotFoundAuthCodeException::class);
        $content = $this->getContent();

        $config = new Config();
        $config->setUseUsername(false);
        $config->setDenyIfNotVerifiedToSignIn(false);
        $config->setDenyIfNotVerifiedToResetPw(false);

        (new SignUp(new Data($content), $config))->triggerRun();

        (new ResetPassword(new Data([
            'email' => $content['email'],
            'newPassword' => 'new' . $content['password'],
            'authCode' => 'not-set',
        ]), $config))->triggerRun();
    }

    public function testRunDenyIfNotVerifiedToResetPWTrue()
    {
        $this->expectException(NotVerifiedEmailException::class);
        $content = $this->getContent();

        $config = new Config();
        $config->setUseUsername(false);
        $config->setDenyIfNotVerifiedToSignIn(false);
        $config->setDenyIfNotVerifiedToResetPw(true);

        (new SignUp(new Data($content), $config))->triggerRun();

        ($setAuth = new SetAuthCodeToResetPassword(new Data([
            'email'=>$content['email']
        ]), $config))->triggerRun();

        (new ResetPassword(new Data([
            'email' => $content['email'],
            'newPassword' => 'new' . $content['password'],
            'authCode' => $setAuth->getResponse()->getContent()['authCode'],
        ]), $config))->triggerRun();
    }

    public function testRunTrialCountOverException()
    {
        $this->expectException(TrialCountOverException::class);
        $content = $this->getContent();

        $config = new Config();
        $config->setUseUsername(false);
        $config->setDenyIfNotVerifiedToSignIn(false);
        $config->setDenyIfNotVerifiedToResetPw(false);

        (new SignUp(new Data($content), $config))->triggerRun();

        ($setAuth = new SetAuthCodeToResetPassword(new Data([
            'email'=>$content['email']
        ]), $config))->triggerRun();

        for($i = 0; $i < $config->getTrialMaxCountToResetPw(); $i++) {
            try {
                (new ResetPassword(new Data([
                    'email' => $content['email'],
                    'newPassword' => 'new' . $content['password'],
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
        $config->setUseUsername(false);
        $config->setDenyIfNotVerifiedToSignIn(false);
        $config->setDenyIfNotVerifiedToResetPw(false);
        $config->setValidityTimeToResetPw($validityTime);

        (new SignUp(new Data($content), $config))->triggerRun();

        ($setAuth = new SetAuthCodeToResetPassword(new Data([
            'email'=>$content['email']
        ]), $config))->triggerRun();

        $user = User::getByEmail($content['email']);
        $auth = AuthenticationCode::getByUserIdType($user->getId(), AuthConfig::TYPE_RESET_PW);

        $dateTime = new \DateTime();
        $dateTime->setTimestamp($auth->getDateTime()->getTimestamp() + $requestTime);
        (new ResetPassword(new Data([
            'email' => $content['email'],
            'newPassword' => 'new' . $content['password'],
            'authCode' => $setAuth->getResponse()->getContent()['authCode'],
        ]), $config, null, $dateTime))->triggerRun();

        if(!$exception) {
            $this->assertTrue(User::checkPassword($user, 'new' . $content['password']));
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
            'email'=>'email@example.com',
            'password'=>'p a s s w o r d',
        ];
    }
}
