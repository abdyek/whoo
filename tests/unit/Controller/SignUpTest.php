<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Controller\SignUp;
use Abdyek\Whoo\Core\Data;
use Abdyek\Whoo\Core\Config;
use Abdyek\Whoo\Exception\NotUniqueEmailException;
use Abdyek\Whoo\Exception\NotUniqueUsernameException;
use Abdyek\Whoo\Exception\UnmatchedPasswordsException;
use Abdyek\Whoo\Model\User;

/**
 * @covers SignUp::
 */

class SignUpTest extends TestCase
{
    private const USERNAME = 'thisIsUsername';
    use Reset;
    public function setUp(): void
    {
        self::reset();
    }
    public function testRun()
    {
        $content = $this->getContent();

        $signUp = new SignUp(new Data($content));
        $signUp->triggerRun();

        $this->assertNotNull($signUp->getResponse()->getContent()['user']);
        $this->assertEquals(60, strlen($signUp->getResponse()->getContent()['tempToken']));
    }
    /**
     * @dataProvider trueFalse
     */
    public function testRunDefault2FA($val)
    {
        $content = $this->getContent();
        $signUp = new SignUp(new Data($content));

        $config = $signUp->getConfig();
        $config->setDefault2fa($val);

        $signUp->triggerRun();

        $response = $signUp->getResponse();
        $default2fa = $response->getContent()['user']->getTwoFactorAuthentication();
        $this->assertEquals($val, $default2fa);
    }

    public function testRunNotUniqueEmailException()
    {
        $this->expectException(NotUniqueEmailException::class);
        $content = $this->getContent();

        for($i = 0; $i<2; $i++) {
            // run two times to throw NotUniqueEmailException
            (new SignUp(new Data($content)))->triggerRun();
        }
    }

    public function testRunOptionalUsername()
    {
        $content = $this->getContent();
        $content['username'] = self::USERNAME;

        $signUp = new SignUp(new Data($content));

        $config = $signUp->getConfig();
        $config->setUseUsername(true);

        $signUp->triggerRun();

        $user = User::getByEmail($content['email']);
        $this->assertEquals(self::USERNAME, $user->getUsername());
    }

    public function testRunOptionalPasswordAgain()
    {
        $content = $this->getContent();
        $content['passwordAgain'] = $content['password'];

        $signUp = new SignUp(new Data($content));
        $signUp->triggerRun();

        $user = User::getByEmail($content['email']);
        $this->assertSame($user, $signUp->getResponse()->getContent()['user']);
    }

    public function testRunUnmatchedPasswordsException()
    {
        $this->expectException(UnmatchedPasswordsException::class);

        $content = $this->getContent();
        $content['passwordAgain'] = $content['password'] . '!wrong!';

        $signUp = new SignUp(new Data($content));
        $signUp->triggerRun();
    }
    
    public function testRunNotUniqueUsernameException()
    {
        $this->expectException(NotUniqueUsernameException::class);

        $content = $this->getContent();
        $content['username'] = self::USERNAME;

        $config = new Config();
        $config->setUseUsername(true);

        (new SignUp(new Data($content), $config))->triggerRun();

        $content['email'] = 'different' . $content['email'];

        (new SignUp(new Data($content), $config))->triggerRun();
    }

    private function getContent() {
        return [
            'email'=>'example@example.com',
            'password'=>'123123123121'
        ];
    }
    public function trueFalse() {
        return [
            [true],
            [false]
        ];
    }
}
