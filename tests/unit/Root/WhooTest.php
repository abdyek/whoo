<?php

use PHPUnit\Framework\TestCase;

use Abdyek\Whoo\Whoo;
use Abdyek\Whoo\Core\Config;

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

    public function testCatchException()
    {
        $whoo = new Whoo('SignUp');
        $val = true;
        $whoo->catchException('InvalidData', function() {
            $this->assertTrue(true);
        });
        $whoo->run();
    }

    public function testSetConfig()
    {
        $config = new Config();
        Whoo::setConfig($config);
        $whoo = new Whoo('SignUp');
        $this->assertSame($config, $whoo->getController()->getConfig());
    }

    public function testContent()
    {
        $whoo = new Whoo('SignUp', [
            'email' => 'example@example.com',
            'password' => '123123123123'
        ]);
        $whoo->run();

        $this->assertNotNull($whoo->content['user']);
        $this->assertNotNull($whoo->content['tempToken']);
    }

}
