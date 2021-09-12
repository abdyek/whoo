<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Tool\Config;
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

/**
 * @covers SignIn::
 */

class SignInTest extends TestCase {
    const USERNAME = 'usernamee';
    use Reset;
    use ChangeConfig;
    public static function setUpBeforeClass(): void {
        Config::setPropelConfigDir('propel/config.php');
        Config::load(); // for reset
    }
    public function setUp(): void {
        self::reset();
    }
    public function testRun() {
        $data = $this->getData();
        $signUp = new SignUp($data);
        $config = $this->changeConfig([
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>false,
            'USE_USERNAME'=>false
        ]);
        $signIn = new SignIn($data, $config);
        $decoded = (array) JWT::getPayload($signIn->jwt);
        $this->assertNotNull($decoded);
        $this->assertNotNull($signIn->user);
        $this->assertNull($signIn->temporaryToken);
        $this->assertEquals($signIn->user->getId(), $decoded['userId']);
        $signIn = new SignIn($data, $config);
    }
    public function testRunUseUsernameTrue() {
        $data = $this->getData();
        $config = $this->changeConfig([
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>false,
            'USE_USERNAME'=>true,
            'DENY_IF_NOT_SET_USERNAME'=>true
        ]);
        $signUp = new SignUp($data, $config);
        new SetUsername([
            'temporaryToken'=>$signUp->temporaryToken,
            'username'=>self::USERNAME
        ], $config);
        $signIn = new SignIn($data, $config);
        $decoded = (array) JWT::getPayload($signIn->jwt);
        $this->assertNotNull($decoded);
        $this->assertNotNull($signIn->user);
        $this->assertEquals($signIn->user->getId(), $decoded['userId']);
        $this->assertSame(self::USERNAME, $signIn->user->getUsername());
        $this->assertNull($signIn->temporaryToken);
    }
    public function testRunDenyIfNotSetUsernameFalse() {
        $data = $this->getData();
        $config = $this->changeConfig([
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>false,
            'USE_USERNAME'=>true,
            'DENY_IF_NOT_SET_USERNAME'=>false
        ]);
        $signUp = new SignUp($data, $config);
        $signIn = new SignIn($data, $config);
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
        $config = $this->changeConfig([
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>true,
            'USE_USERNAME'=>false
        ]);
        new SignIn($data, $config);
    }
    public function testRunNullUsernameException() {
        $this->expectException(NullUsernameException::class);
        $data = $this->getData();
        $signUp = new SignUp($data);
        $config = $this->changeConfig([
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>false,
            'USE_USERNAME'=>true
        ]);
        new SignIn($data, $config);
    }
    public function testRun2FA() {
        $config = $this->changeConfig([
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>false,
            'USE_USERNAME'=>false,
            'DEFAULT_2FA'=>true
        ]);
        $data = $this->getData();
        new SignUp($data, $config);
        try {
            new SignIn($data, $config);
        } catch(TwoFactorAuthEnabledException $e) {
            $user = User::getByEmail($data['email']);
            $code = AuthenticationCode::getByUserIdType($user->getId(), AuthConfig::TYPE_2FA);
            $this->assertSame($code->getCode(), $e->authenticationCode);
        }
    }
    public function testRun2FAWithException() {
        $this->expectException(TwoFactorAuthEnabledException::class);
        $config = $this->changeConfig([
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>false,
            'USE_USERNAME'=>false,
            'DEFAULT_2FA'=>true
        ]);
        $data = $this->getData();
        new SignUp($data, $config);
        new SignIn($data, $config);
    }
    public function testRunOptionalPasswordAgain() {
        $config = $this->changeConfig([
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>false,
            'USE_USERNAME'=>false,
            'DEFAULT_2FA'=>false
        ]);
        $data = $this->getData();
        new SignUp($data, $config);
        $data['passwordAgain'] = $data['password'];
        $signIn = new SignIn($data, $config);
        $payload = JWT::getPayload($signIn->jwt);
        $this->assertEquals($signIn->user->getId(), $payload['userId']);
    }
    public function testRunUnmatchedPasswordsException() {
        $this->expectException(UnmatchedPasswordsException::class);
        $config = $this->changeConfig([
            'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>false,
            'USE_USERNAME'=>false,
            'DEFAULT_2FA'=>false
        ]);
        $data = $this->getData();
        new SignUp($data, $config);
        $data['passwordAgain'] = 'wrong-password';
        new SignIn($data, $config);
    }
    private function getData() {
        return [
            'email'=>'example@example.com',
            'password'=>'this is too secret password'
        ];
    }
}
