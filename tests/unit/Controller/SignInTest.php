<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Controller\SignUp;
use Abdyek\Whoo\Controller\SignIn;
use Abdyek\Whoo\Controller\SetUsername;
use Abdyek\Whoo\Model\User;
use Abdyek\Whoo\Model\AuthenticationCode;
use Abdyek\Whoo\Exception\NotFoundException;
use Abdyek\Whoo\Exception\IncorrectPasswordException;
use Abdyek\Whoo\Exception\NotVerifiedEmailException;
use Abdyek\Whoo\Exception\NullUsernameException;
use Abdyek\Whoo\Exception\TwoFactorAuthEnabledException;
use Abdyek\Whoo\Exception\UnmatchedPasswordsException;
use Abdyek\Whoo\Config\JWT as JWTConfig;
use Abdyek\Whoo\Config\Authentication as AuthConfig;
use Abdyek\Whoo\Tool\JWT;
use Abdyek\Whoo\Config\Whoo as Config;
use Abdyek\Whoo\Config\Propel as PropelConfig;

/**
 * @covers SignIn::
 */

class SignInTest extends TestCase {
    const USERNAME = 'usernamee';
    use Reset;
    public static function setUpBeforeClass(): void {
        PropelConfig::$CONFIG_FILE = 'propel/config.php';
    }
    public function setUp(): void {
        self::reset();
    }
    public function testRun() {
        Config::$DENY_IF_NOT_VERIFIED_TO_SIGN_IN = false;
        Config::$USE_USERNAME = false;
        Config::$DEFAULT_2FA = false;
        $data = $this->getData();
        $signUp = new SignUp($data);
        $signIn = new SignIn($data);
        $decoded = (array) JWT::getPayload($signIn->jwt);
        $this->assertNotNull($decoded);
        $this->assertNotNull($signIn->user);
        $this->assertNull($signIn->temporaryToken);
        $this->assertEquals($signIn->user->getId(), $decoded['userId']);
        $signIn = new SignIn($data);
    }
    public function testRunUseUsernameTrue() {
        $data = $this->getData();
        Config::$DENY_IF_NOT_VERIFIED_TO_SIGN_IN = false;
        Config::$USE_USERNAME = true;
        Config::$DENY_IF_NOT_SET_USERNAME = true;
        Config::$DEFAULT_2FA = false;
        $signUp = new SignUp($data);
        new SetUsername([
            'temporaryToken'=>$signUp->temporaryToken,
            'username'=>self::USERNAME
        ]);
        $signIn = new SignIn($data);
        $decoded = (array) JWT::getPayload($signIn->jwt);
        $this->assertNotNull($decoded);
        $this->assertNotNull($signIn->user);
        $this->assertEquals($signIn->user->getId(), $decoded['userId']);
        $this->assertSame(self::USERNAME, $signIn->user->getUsername());
        $this->assertNull($signIn->temporaryToken);
    }
    public function testRunDenyIfNotSetUsernameFalse() {
        Config::$DENY_IF_NOT_VERIFIED_TO_SIGN_IN = false;
        Config::$USE_USERNAME = true;
        Config::$DENY_IF_NOT_SET_USERNAME = false;
        Config::$DEFAULT_2FA = false;
        $data = $this->getData();
        $signUp = new SignUp($data);
        $signIn = new SignIn($data);
        $decoded = (array) JWT::getPayload($signIn->jwt);
        $this->assertNotNull($decoded);
        $this->assertNotNull($signIn->user);
        $this->assertEquals($signIn->user->getId(), $decoded['userId']);
        $this->assertNotNull($signIn->temporaryToken);
    }
    public function testNotFoundException() {
        $this->expectException(NotFoundException::class);
        $data = $this->getData();
        $data['email'] = 'notFoundEmail@123.com';
        $signIn = new SignIn($data);
    }
    public function testRunIncorrectPasswordException() {
        $this->expectException(IncorrectPasswordException::class);
        $data = $this->getData();
        $signUp = new SignUp($data);
        $data['password'] = 'wrong password';
        $signIn = new SignIn($data);
    }
    public function testRunNotVerifiedEmailException() {
        $this->expectException(NotVerifiedEmailException::class);
        $data = $this->getData();
        $signUp = new SignUp($data);
        Config::$DENY_IF_NOT_VERIFIED_TO_SIGN_IN = true;
        Config::$USE_USERNAME = false;
        new SignIn($data);
    }
    public function testRunNullUsernameException() {
        $this->expectException(NullUsernameException::class);
        Config::$USE_USERNAME = true;
        Config::$DEFAULT_2FA = false;
        Config::$DENY_IF_NOT_VERIFIED_TO_SIGN_IN = false;
        Config::$DENY_IF_NOT_SET_USERNAME = true;
        $data = $this->getData();
        $signUp = new SignUp($data);
        new SignIn($data);
    }
    public function testRun2FA() {
        Config::$DENY_IF_NOT_VERIFIED_TO_SIGN_IN = false;
        Config::$USE_USERNAME = false;
        Config::$DEFAULT_2FA = true;
        $data = $this->getData();
        new SignUp($data);
        try {
            new SignIn($data);
        } catch(TwoFactorAuthEnabledException $e) {
            $user = User::getByEmail($data['email']);
            $code = AuthenticationCode::getByUserIdType($user->getId(), AuthConfig::TYPE_2FA);
            $this->assertSame($code->getCode(), $e->authenticationCode);
        }
    }
    public function testRun2FAWithException() {
        $this->expectException(TwoFactorAuthEnabledException::class);
        Config::$DENY_IF_NOT_VERIFIED_TO_SIGN_IN = false;
        Config::$USE_USERNAME = false;
        Config::$DEFAULT_2FA = true;
        $data = $this->getData();
        new SignUp($data);
        new SignIn($data);
    }
    public function testRunOptionalPasswordAgain() {
        Config::$DENY_IF_NOT_VERIFIED_TO_SIGN_IN = false;
        Config::$USE_USERNAME = false;
        Config::$DEFAULT_2FA = false;
        $data = $this->getData();
        new SignUp($data);
        $data['passwordAgain'] = $data['password'];
        $signIn = new SignIn($data);
        $payload = JWT::getPayload($signIn->jwt);
        $this->assertEquals($signIn->user->getId(), $payload['userId']);
    }
    public function testRunUnmatchedPasswordsException() {
        $this->expectException(UnmatchedPasswordsException::class);
        Config::$DENY_IF_NOT_VERIFIED_TO_SIGN_IN = false;
        Config::$USE_USERNAME = false;
        Config::$DEFAULT_2FA = false;
        $data = $this->getData();
        new SignUp($data);
        $data['passwordAgain'] = 'wrong-password';
        new SignIn($data);
    }
    private function getData() {
        return [
            'email'=>'example@example.com',
            'password'=>'this is too secret password'
        ];
    }
}
