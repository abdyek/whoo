<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Core\Core;
use Pseudo\ExampleController;
use Pseudo\AnotherCore;

/**
 * @covers Core::
 */

class CoreTest extends TestCase
{
    public function test()
    {
        $exampleController = new ExampleController(['a'=>'b']);
        $core = new AnotherCore;
        $core->setController($exampleController);
        $controller = $core->getController();
        $this->assertSame('b', $controller->getData()['a']);
    }
}
