<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Controller\VerifyEmail;
use Abdyek\Whoo\Controller\SetAuthCodeForEmailVerification;
use Abdyek\Whoo\Exception\InvalidCodeException;
use Abdyek\Whoo\Exception\TrialCountOverException;
use Abdyek\Whoo\Exception\NotFoundException;
use Abdyek\Whoo\Exception\NotFoundAuthCodeException;
use Abdyek\Whoo\Config\Authentication as AuthConfig;
use Abdyek\Whoo\Model\AuthenticationCode;
use Abdyek\Whoo\Config\Propel as PropelConfig;

/**
 * @covers VerifyEmail::
 */

class VerifyEmailTest extends TestCase {
    use DefaultConfig;
    use Reset;
    use UserTool;
    public static function setUpBeforeClass(): void {
        PropelConfig::$CONFIG_FILE = 'propel/config.php';
    }
    public function setUp(): void {
        self::setDefaultConfig();
        self::reset();
    }
    public function testRunNotFoundExcepiton() {
        $this->expectException(NotFoundException::class);
        new VerifyEmail([
            'email'=>'notFound@notFound.com',
            'authCode'=>'noneNONE'
        ]);
    }
    public function testRunNotFoundAuthCodeException() {
        $this->expectException(NotFoundAuthCodeException::class);
        $user = $this->createExample();
        new VerifyEmail([
            'email'=>self::$traitEmail,
            'authCode'=>'codee'
        ]);
    }
    public function testRunInvalidCodeException() {
        $this->expectException(InvalidCodeException::class);
        $user = $this->createExample();
        new SetAuthCodeForEmailVerification([
            'email'=>self::$traitEmail
        ]);
        new VerifyEmail([
            'email'=>self::$traitEmail,
            'authCode'=>'wrongCode'
        ]);
    }
    public function testRunTrialCountOverException() {
        $this->expectException(TrialCountOverException::class);
        $user = $this->createExample();
        new SetAuthCodeForEmailVerification([
            'email'=>self::$traitEmail
        ]);
        for($i=0;$i<AuthConfig::$TRIAL_MAX_COUNT_TO_VERIFY_EMAIL;$i++) {
            try {
                new VerifyEmail([
                    'email'=>self::$traitEmail,
                    'authCode'=>'wrongCode'
                ]);
            } catch(InvalidCodeException $e) { }
        }
    }
    /*
    public function testRunTimeOutCodeException() {
        // I will fill it after
    }*/
    public function testRun() {
        $user = $this->createExample();
        $setAuth = new SetAuthCodeForEmailVerification([
            'email'=>self::$traitEmail
        ]);
        new VerifyEmail([
            'email'=>self::$traitEmail,
            'authCode'=>$setAuth->authCode
        ]);
        $auth = AuthenticationCode::getByUserIdType($user->getId(), 'emailVerification');
        $this->assertTrue($user->getEmailVerified());
        $this->assertNull($auth);
    }
}
