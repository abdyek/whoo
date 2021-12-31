<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Controller\SetUsername;
use Abdyek\Whoo\Controller\SignUp;
use Abdyek\Whoo\Core\Config;
use Abdyek\Whoo\Core\Data;
use Abdyek\Whoo\Repository\User;
use Abdyek\Whoo\Exception\InvalidTemporaryTokenException;
use Abdyek\Whoo\Exception\NotNullUsernameException;
use Abdyek\Whoo\Exception\NotUniqueUsernameException;

/**
 * @covers SetUsername::
 */

class SetUsernameTest extends TestCase
{
    const USERNAME = 'uSeRNaMe';
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

        $signUp = new SignUp(new Data($content), $config);
        $signUp->triggerRun();

        $responseContent = $signUp->getResponse()->getContent();

        (new SetUsername(new Data([
            'tempToken' => $responseContent['tempToken'],
            'username' => self::USERNAME
        ]), $config))->triggerRun();

        $user = User::getByEmail($content['email']);
        $this->assertEquals(self::USERNAME, $user->getUsername());
    }

    public function testRunInvalidTemporaryTokenException()
    {
        $this->expectException(InvalidTemporaryTokenException::class);
        $content = $this->getContent();

        $config = new Config();
        $config->setUseUsername(true);

        (new SignUp(new Data($content), $config))->triggerRun();

        (new SetUsername(new Data([
            'tempToken'=>'w-r-o-n-g-kW5tqGMKgZTT73GRZcy7ewfFvrZSxbmVQKXcwAA7fkJnthgGf3',
            'username'=>self::USERNAME
        ]), $config))->triggerRun();
    }

    public function testRunNotNullUsernameException()
    {
        $this->expectException(NotNullUsernameException::class);
        $content = $this->getContent();

        $config = new Config();
        $config->setUseUsername(true);

        ($signUp = new SignUp(new Data($content), $config))->triggerRun();

        $tempToken = $signUp->getResponse()->getContent()['tempToken'];

        for($i = 0; $i < 2; $i++) {
            (new SetUsername(new Data([
                'tempToken'=>$tempToken,
                'username'=>self::USERNAME
            ]), $config))->triggerRun();
        }
    }

    public function testRunNotUniqueUsernameException()
    {
        $this->expectException(NotUniqueUsernameException::class);
        $content = $this->getContent();

        $config = new Config();
        $config->setUseUsername(true);

        ($signUp = new SignUp(new Data($content), $config))->triggerRun();

        (new SignUp(new Data([
            'email' => 'otherUser@example.com',
            'password' => '123123123',
            'username' => self::USERNAME
        ]), $config))->triggerRun();

        $tempToken = $signUp->getResponse()->getContent()['tempToken'];

        (new SetUsername(new Data([
            'tempToken'=>$tempToken,
            'username'=>self::USERNAME
        ]), $config))->triggerRun();
    }

    private function getContent(): array
    {
        return [
            'email'=>'example@example.com',
            'password'=>'123123123q'
        ];
    }
}
