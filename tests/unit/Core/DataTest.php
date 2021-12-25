<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Core\Config;
use Abdyek\Whoo\Core\Data;
use Abdyek\Whoo\Exception\InvalidDataException;
use Pseudo\ExampleController;

/**
 * @covers Data::
 */

class DataTest extends TestCase
{
    public function testConstruct()
    {
        $content = [
            'a' => 'b'
        ];
        $data = new Data($content);
        $this->assertSame($content, $data->getContent());
    }

    public function testGetSetContent()
    {
        $content = [
            'a' => 'b'
        ];
        $data = new Data();
        $data->setContent($content);
        $this->assertSame($content, $data->getContent());
    }

}
