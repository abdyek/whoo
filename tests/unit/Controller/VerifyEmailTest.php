<?php

require 'propel/config.php';
use PHPUnit\Framework\TestCase;
use Whoo\Controller\VerifyEmail;
use Whoo\Controller\SetAuthCodeForEmailVerification;
use Whoo\Exception\InvalidCodeException;
use Whoo\Exception\TrialCountOverException;
use Whoo\Config\Authentication as AuthConfig;
use Whoo\Model\AuthenticationCode;

/**
 * @covers VerifyEmail::
 */

class VerifyEmailTest extends TestCase {
    use Reset;
    use UserTool;
    public function setUp(): void {
        self::reset();
    }
    public function testRunInvalidCodeExceptionNoUser() {
        $this->expectException(InvalidCodeException::class);
        new VerifyEmail([
            'email'=>'notFound@notFound.com',
            'code'=>'noneNONE'
        ]);
    }
    public function testRunInvalidCodeExceptionWrongCode() {
        $this->expectException(InvalidCodeException::class);
        $user = $this->createExample();
        new SetAuthCodeForEmailVerification([
            'email'=>self::$traitEmail
        ]);
        new VerifyEmail([
            'email'=>self::$traitEmail,
            'code'=>'wrongCode'
        ]);
    }
    public function testRunTrialCountOverException() {
        $this->expectException(TrialCountOverException::class);
        $user = $this->createExample();
        new SetAuthCodeForEmailVerification([
            'email'=>self::$traitEmail
        ]);
        for($i=0;$i<AuthConfig::TRIAL_MAX_COUNT;$i++) {
            try {
                new VerifyEmail([
                    'email'=>self::$traitEmail,
                    'code'=>'wrongCode'
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
            'code'=>$setAuth->code
        ]);
        $auth = AuthenticationCode::getByUserIdType($user->getId(), 'emailVerification');
        $this->assertTrue($user->getEmailVerified());
        $this->assertNull($auth);
    }
}
