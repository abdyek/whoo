<?php

use PHPUnit\Framework\TestCase;

use Abdyek\Whoo\Whoo;
use Abdyek\Whoo\Core\Config;
use Pseudo\ExampleController;

/**
 * @covers Whoo::
 */

class WhooTest extends TestCase
{
    use Reset;
    public function setUp(): void
    {
        self::reset();
    }

    public function testConstruct()
    {
        $whoo = new Whoo('SignUp');
        $controller = $whoo->getController();
        $className = get_class($controller);
        $this->assertSame('Abdyek\\Whoo\\Controller\\SignUp', $className);
    }

    public function testSuccess()
    {
        $config = new Config();
        $whoo = new Whoo('SignUp', [
            'email' => 'example@example.com',
            'password' => 'this_is_pw',
        ]);
        $whoo->success(function($content) {
            $this->assertNotNull($content['user']);
            $this->assertNotNull($content['tempToken']);
        });
        $whoo->run();
    }

    public function testCatchException()
    {
        $whoo = new Whoo('SignUp');
        $whoo->success(function() {
            $this->assertTrue(false);
        })->exception('InvalidData', function() {
            $this->assertTrue(true);
        });
        $whoo->run();
    }

    public function testGetSetGlobalConfig()
    {
        $config = new Config();
        $config->setValidityTimeToVerifyEmail(123);
        Whoo::setGlobalConfig($config);
        $whoo = new Whoo('SignUp');
        $this->assertEquals(123, $whoo->getController()->getConfig()->getValidityTimeToVerifyEmail());
        $this->assertSame($config, $whoo->getGlobalConfig());
    }

    public function testClaims()
    {
        $claims = [
            'exp' => time() + 360
        ];
        $whoo = new Whoo('SignUp');
        $whoo->claims($claims);
        $this->assertSame($claims, $whoo->getController()->getAuthenticator()->getJWTObject()->getClaims());
    }

}
