<?php

use PHPUnit\Framework\TestCase;

use Abdyek\Whoo\Whoo;

/**
 * @covers Whoo::
 */

class WhooTest extends TestCase
{
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
}
