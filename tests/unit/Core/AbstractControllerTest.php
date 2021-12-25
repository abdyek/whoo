<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Core\AbstractController;
use Abdyek\Whoo\Core\Data;
use Abdyek\Whoo\Core\Config;
use Abdyek\Whoo\Core\Validator;
use Pseudo\ExampleController;

/**
 * @covers AbstractController::
 */

class AbstractControllerTest extends TestCase
{
    public function testGetClassName()
    {
        $exampleController = new ExampleController();
        $this->assertSame('ExampleController', $exampleController->getClassName());
    }

    public function testGetSetData()
    {
        $exampleController = new ExampleController(new Data(['a' => 'b']));
        $exampleController2 = new ExampleController(new Data(['a' => 'c']));
        $exampleController->setData($exampleController2->getData());
        $this->assertSame('c', $exampleController->getData()->getContent()['a']);
    }

    public function testGetSetConfig()
    {
        $config = new Config;
        $exampleController = new ExampleController();
        $exampleController->setConfig($config);
        $this->assertSame($config, $exampleController->getConfig());
    }

    public function testGetSetValidator()
    {
        $validator = new Validator;
        $exampleController = new ExampleController();
        $exampleController->setValidator($validator);
        $this->assertSame($validator, $exampleController->getValidator());
    }

    public function testGetSetDateTime()
    {
        $dateTime = new \DateTime('1994-01-01');
        $exampleController = new ExampleController();
        $exampleController->setDateTime($dateTime);
        $this->assertSame('1994-01', $exampleController->getDateTime()->format('Y-m'));
    }

}
