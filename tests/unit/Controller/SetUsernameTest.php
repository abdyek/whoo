<?php

require 'propel/config.php';
use PHPUnit\Framework\TestCase;
use Whoo\Controller\SetUsername;
use Whoo\Controller\SignUp;
use Whoo\Model\User as UserModel;
use Whoo\Exception\InvalidTemporaryTokenException;
use Whoo\Exception\NotNullUsernameException;
use Whoo\Exception\NotUniqueUsernameException;

/**
 * @covers SetUsername::
 */

class SetUsernameTest extends TestCase {
    const USERNAME = 'uSeRNaMe';
    use Reset;
    use ChangeConfig;
    public function setUp(): void {
        self::reset();
    }
    public function testRun() {
        $data = $this->getData();
        $config = $this->changeConfig([
            'USE_USERNAME'=>true
        ]);
        $signUp = new SignUp($data, $config);
        new SetUsername([
            'temporaryToken'=>$signUp->temporaryToken,
            'username'=>self::USERNAME
        ], $config);
        $user = UserModel::getByEmail($data['email']);
        $this->assertSame(self::USERNAME, $user->getUsername());
    }
    public function testRunInvalidTemporaryTokenException() {
        $this->expectException(InvalidTemporaryTokenException::class);
        $data = $this->getData();
        $signUp = new SignUp($data);
        new SetUsername([
            'temporaryToken'=>'Zvix5wJf3VkW5tqGMKgZTT73GRZcy7ewfFvrZSxbmVQKXcwAA7fkJnthgGf3',
            'username'=>self::USERNAME
        ]);
    }
    public function testRunNotNullUsernameException() {
        $this->expectException(NotNullUsernameException::class);
        $config = $this->changeConfig([
            'USE_USERNAME'=>true
        ]);
        $data = $this->getData();
        $signUp = new SignUp($data, $config);
        new SetUsername([
            'temporaryToken'=>$signUp->temporaryToken,
            'username'=>self::USERNAME
        ], $config);
        new SetUsername([
            'temporaryToken'=>$signUp->temporaryToken,
            'username'=>self::USERNAME
        ], $config);
    }
    public function testRunNotUniqueUsernameException() {
        $this->expectException(NotUniqueUsernameException::class);
        $config = $this->changeConfig([
            'USE_USERNAME'=>true
        ]);
        $data = $this->getData();
        $signUp = new SignUp($data, $config);
        new SetUsername([
            'temporaryToken'=>$signUp->temporaryToken,
            'username'=>self::USERNAME
        ], $config);
        $otherUser = new SignUp([
            'email'=>'other@user.com',
            'password'=>'top_secret_pass'
        ], $config);
        new SetUsername([
            'temporaryToken'=>$otherUser->temporaryToken,
            'username'=>self::USERNAME
        ], $config);
    }
    private function getData() {
        return [
            'email'=>'abc@bcd.com',
            'password'=>'123123123q'
        ];
    }
}
