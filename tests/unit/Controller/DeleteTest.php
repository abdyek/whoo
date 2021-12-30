<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Controller\Delete;
use Abdyek\Whoo\Controller\SignUp;
use Abdyek\Whoo\Controller\SignIn;
use Abdyek\Whoo\Core\Config;
use Abdyek\Whoo\Core\Data;
use Abdyek\Whoo\Exception\IncorrectPasswordException;

/**
 * @covers Delete::
 */

class DeleteTest extends TestCase
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

        $responseContent = $signIn->getResponse()->getContent();
        
        (new Delete(new Data([
            'jwt' => $responseContent['jwt'],
            'password' => $content['password'],
        ]), $config))->triggerRun();

        $this->assertTrue($responseContent['user']->isDeleted());

    }

    public function testRunIncorrectPasswordException()
    {
        $this->expectException(IncorrectPasswordException::class);
        $content = $this->getContent();

        $config = new Config();
        $config->setUseUsername(false);
        $config->setDenyIfNotVerifiedToSignIn(false);
        $config->setDefault2fa(false);

        (new SignUp(new Data($content), $config))->triggerRun();

        ($signIn = new SignIn(new Data($content), $config))->triggerRun();

        (new Delete(new Data([
            'jwt' => $signIn->getResponse()->getContent()['jwt'],
            'password' => 'wrong' . $content['password'],
        ]), $config))->triggerRun();

    }

    private function getContent(): array
    {
        return [
            'email' => 'example@example.com',
            'password' => 'this_is_password',
        ];
    }
}
