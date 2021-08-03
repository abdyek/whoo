<?php

require 'propel/config.php';
use PHPUnit\Framework\TestCase;
use Whoo\Controller\SetUsername;
use Whoo\Controller\SignUp;
use Whoo\Model\Member as MemberModel;
use Whoo\Exception\InvalidTemporaryTokenException;
use Whoo\Exception\NotNullUsernameException;
use Whoo\Exception\NotUniqueUsernameException;

/**
 * @covers SetUsername::
 */

class SetUsernameTest extends TestCase {
    const USERNAME = 'uSeRNaMe';
    use Reset;
    public function setUp(): void {
        self::reset();
    }
    public function testRun() {
        $data = $this->getData();
        $signUp = new SignUp($data);
        new SetUsername([
            'temporaryToken'=>$signUp->temporaryToken,
            'username'=>self::USERNAME
        ]);
        $member = MemberModel::getByEmail($data['email']);
        $this->assertSame(self::USERNAME, $member->getUsername());
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
        $data = $this->getData();
        $signUp = new SignUp($data);
        new SetUsername([
            'temporaryToken'=>$signUp->temporaryToken,
            'username'=>self::USERNAME
        ]);
        new SetUsername([
            'temporaryToken'=>$signUp->temporaryToken,
            'username'=>self::USERNAME
        ]);
    }
    public function testRunNotUniqueUsernameException() {
        $this->expectException(NotUniqueUsernameException::class);
        $data = $this->getData();
        $signUp = new SignUp($data);
        new SetUsername([
            'temporaryToken'=>$signUp->temporaryToken,
            'username'=>self::USERNAME
        ]);
        $otherUser = new SignUp([
            'email'=>'other@user.com',
            'password'=>'top_secret_pass'
        ]);
        new SetUsername([
            'temporaryToken'=>$otherUser->temporaryToken,
            'username'=>self::USERNAME
        ]);
    }
    private function getData() {
        return [
            'email'=>'abc@bcd.com',
            'password'=>'123123123q'
        ];
    }
}
