<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Controller\SignUp;
use Abdyek\Whoo\Controller\SignIn;
use Abdyek\Whoo\Controller\SetUsername;
use Abdyek\Whoo\Core\Config;
use Abdyek\Whoo\Core\Data;
use Abdyek\Whoo\Repository\User;
use Abdyek\Whoo\Repository\AuthenticationCode;
use Abdyek\Whoo\Exception\NotFoundException;
use Abdyek\Whoo\Exception\IncorrectPasswordException;
use Abdyek\Whoo\Exception\NotVerifiedEmailException;
use Abdyek\Whoo\Exception\NullUsernameException;
use Abdyek\Whoo\Exception\TwoFactorAuthEnabledException;
use Abdyek\Whoo\Exception\UnmatchedPasswordsException;
use Abdyek\Whoo\Config\JWT as JWTConfig;
use Abdyek\Whoo\Config\Authentication as AuthConfig;
use Abdyek\Whoo\Tool\JWT;
use Abdyek\Whoo\Tool\TemporaryToken;

/**
 * @covers SignIn::
 */

class SignInTest extends TestCase
{
    private const USERNAME = 'usernamee';

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

        $signIn = new SignIn(new Data($content), $config);
        $signIn->triggerRun();
    
        $response = $signIn->getResponse();
        $responseContent = $response->getContent();
        $jwt = $responseContent['jwt'];
        $user = $responseContent['user'];

        $jwtObject = new JWT;
        $payload = $jwtObject->payload($jwt);

        $this->assertNotNull($jwt);

        $this->assertEquals($user->getId(), $payload['whoo']['userId']);
    }

    public function testNotFoundException()
    {
        $this->expectException(NotFoundException::class);
        $content = $this->getContent();
        $content['email'] = 'notFound' . $content['email'];
        (new SignIn(new Data($content)))->triggerRun();
    }
    
    public function testRunIncorrectPasswordException()
    {
        $this->expectException(IncorrectPasswordException::class);

        $content = $this->getContent();

        (new SignUp(new Data($content)))->triggerRun();
        $content['password'] = 'wrong pw' . $content['password'];
        $signIn = new SignIn(new Data($content));

        $config = $signIn->getConfig();
        $config->setDenyIfNotVerifiedToSignIn(false);
        $config->setUseUsername(false);

        $signIn->triggerRun();
    }

    public function testRunNotVerifiedEmailException()
    {
        $this->expectException(NotVerifiedEmailException::class);
        $content = $this->getContent();

        (new SignUp(new Data($content)))->triggerRun();
        
        $config = new Config();
        $config->setDenyIfNotVerifiedToSignIn(true);

        (new SignIn(new Data($content), $config))->triggerRun();
    }

    public function testRunAuthCode()
    {
        $content = $this->getContent();

        (new SignUp(new Data($content)))->triggerRun();

        try {
            $config = new Config();
            $config->setDenyIfNotVerifiedToSignIn(true);
            (new SignIn(new Data($content), $config))->triggerRun();
        } catch(NotVerifiedEmailException $e) {
            $user = User::getByEmail($content['email']);
            $authCode = AuthenticationCode::getByUserIdType($user->getId(), AuthConfig::TYPE_EMAIL_VERIFICATION);
        }
        $this->assertSame($authCode->getCode(), $e->authCode);
    }

    public function testRunNullUsernameException()
    {
        $this->expectException(NullUsernameException::class);
        $content = $this->getContent();

        $config = new Config();
        $config->setUseUsername(true);
        $config->setDenyIfNotVerifiedToSignIn(false);

        (new SignUp(new Data($content), $config))->triggerRun();

        (new SignIn(new Data($content), $config))->triggerRun();
    }

    public function testRunTemporaryToken()
    {
        $content = $this->getContent();

        $config = new Config();
        $config->setUseUsername(true);
        $config->setDenyIfNotVerifiedToSignIn(false);

        (new SignUp(new Data($content), $config))->triggerRun();

        try {
            ($signIn = new SignIn(new Data($content), $config))->triggerRun();
        } catch(NullUsernameException $e) {
            $user = User::getByEmail($content['email']);
            $this->assertSame(TemporaryToken::generate($user->getId(), $signIn->getConfig()->getSecretKey()), $e->tempToken);
        }
    }

    public function testRun2FAEnabledException() {
        $this->expectException(TwoFactorAuthEnabledException::class);
        $content = $this->getContent();

        $config = new Config();
        $config->setUseUsername(false);
        $config->setDenyIfNotVerifiedToSignIn(false);
        $config->setDefault2fa(true);

        (new SignUp(new Data($content), $config))->triggerRun();

        (new SignIn(new Data($content), $config))->triggerRun();
    }

    public function testRun2FA()
    {
        $content = $this->getContent();

        $config = new Config();
        $config->setUseUsername(false);
        $config->setDenyIfNotVerifiedToSignIn(false);
        $config->setDefault2fa(true);

        (new SignUp(new Data($content), $config))->triggerRun();

        try {
            (new SignIn(new Data($content), $config))->triggerRun();
        } catch(TwoFactorAuthEnabledException $e) {
            $user = User::getByEmail($content['email']);
            $authCode = AuthenticationCode::getByUserIdType($user->getId(), AuthConfig::TYPE_2FA);
            $this->assertSame($authCode->getCode(), $e->authCode);
        }
    }

    public function testRunUnmatchedPasswordsException()
    {
        $this->expectException(UnmatchedPasswordsException::class);
        $content = $this->getContent();

        $config = new Config();
        $config->setDefault2fa(false);
        $config->setDenyIfNotVerifiedToSignIn(false);
        $config->setUseUsername(false);

        (new SignUp(new Data($content), $config))->triggerRun();

        $content['passwordAgain'] = 'wrong-pw' . $content['password'];

        (new SignIn(new Data($content), $config))->triggerRun();

    }

    public function testRunOptionalPasswordAgain()
    {
        $content = $this->getContent();

        $config = new Config();
        $config->setDefault2fa(false);
        $config->setDenyIfNotVerifiedToSignIn(false);
        $config->setUseUsername(false);

        (new SignUp(new Data($content), $config))->triggerRun();

        $content['passwordAgain'] = $content['password'];

        $signIn = new SignIn(new Data($content), $config);
        $signIn->triggerRun();

        $response = $signIn->getResponse();
        $responseContent = $response->getContent();

        $user = $responseContent['user'];
        $jwt = $responseContent['jwt'];

        $jwtObject = new JWT();
        $payload = $jwtObject->payload($jwt);
        $this->assertEquals($user->getId(), $payload['whoo']['userId']);
    }

    private function getContent(): array
    {
        return [
            'email'=>'example@example.com',
            'password'=>'this is too secret password'
        ];
    }
}
