<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Controller\SetUsername;
use Abdyek\Whoo\Controller\SignUp;
use Abdyek\Whoo\Model\User as UserModel;
use Abdyek\Whoo\Exception\InvalidTemporaryTokenException;
use Abdyek\Whoo\Exception\NotNullUsernameException;
use Abdyek\Whoo\Exception\NotUniqueUsernameException;
use Abdyek\Whoo\Config\Whoo as Config;
use Abdyek\Whoo\Config\Propel as PropelConfig;

/**
 * @covers SetUsername::
 */

class SetUsernameTest extends TestCase {
    const USERNAME = 'uSeRNaMe';
    use Reset;
    public static function setUpBeforeClass(): void {
        PropelConfig::$CONFIG_FILE = 'propel/config.php';
    }
    public function setUp(): void {
        self::reset();
    }
    public function testRun() {
        $data = $this->getData();
        Config::$USE_USERNAME = true;
        $signUp = new SignUp($data);
        new SetUsername([
            'temporaryToken'=>$signUp->temporaryToken,
            'username'=>self::USERNAME
        ]);
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
        Config::$USE_USERNAME = true;
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
        Config::$USE_USERNAME = true;
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
