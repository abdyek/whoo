<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Controller\SignInByProvider;
use Abdyek\Whoo\Controller\SignUp;
use Abdyek\Whoo\Core\Config;
use Abdyek\Whoo\Core\Data;
use Abdyek\Whoo\Exception\NullUsernameException;
use Abdyek\Whoo\Exception\SignUpByEmailException;
use Abdyek\Whoo\Repository\User;
use Abdyek\Whoo\Tool\TemporaryToken;
use Abdyek\Whoo\Tool\JWT;

/**
 * @covers SignInByProvider::
 */

class SignInByProviderTest extends TestCase
{
    use Reset;

    public function setUp(): void
    {
        self::reset();
    }

    /**
     * @dataProvider providerForRun
     */

    public function testRun(bool $first)
    {
        $content = $this->getContent();

        $config = new Config();
        $config->setUseUsername(false);

        if(!$first) {
            ($signIn = new SignInByProvider(new Data($content), $config))->triggerRun();
        }
        // ^ if it is not first, this means it sign in before.

        ($signIn = new SignInByProvider(new Data($content), $config))->triggerRun();

        $responseContent = $signIn->getResponse()->getContent();
        $user = User::getByEmail($content['email']);

        $jwtObject = new JWT;
        $payload = $jwtObject->payload($responseContent['jwt']);
        $this->assertEquals($user->getId(), $payload['whoo']['userId']);
    
        $this->assertSame($first, $responseContent['firstSignIn']);
    }

    public function providerForRun(): array
    {
        return [ 
            [true],
            [false],
        ];
    }

    public function testNullUsernameException()
    {
        $this->expectException(NullUsernameException::class);
        $content = $this->getContent();

        $config = new Config();
        $config->setUseUsername(true);

        (new SignInByProvider(new Data($content), $config))->triggerRun();
    }

    /**
     * @dataProvider providerForSignUpByEmail
     */
    public function testSignUpByEmailException(bool $deny)
    {
        if($deny) {
            $this->expectException(SignUpByEmailException::class);
        }
        $content = $this->getContent();

        $config = new Config();
        $config->setUseUsername(true);
        $config->setDenyIfSignUpBeforeByEmail($deny);

        (new SignUp(new Data([
            'email' => $content['email'],
            'password' => 'password',
            'username' => 'username',
        ]), $config))->triggerRun();

        ($signIn = new SignInByProvider(new Data($content), $config))->triggerRun();

        if(!$deny) {
            $user = User::getByEmail($content['email']);
            $jwtObject = new JWT;
            $payload = $jwtObject->payload($signIn->getResponse()->getContent()['jwt']);
            $this->assertEquals($user->getId(), $payload['whoo']['userId']);
        }
    }

    public function providerForSignUpByEmail(): array
    {
        return [
            [true],
            [false],
        ];
    }

    private function getContent(): array
    {
        return [
            'email' => 'email@example.com',
            'provider' => 'google',
            'providerId' => 'this_is_provider_id',
        ];
    }
}
