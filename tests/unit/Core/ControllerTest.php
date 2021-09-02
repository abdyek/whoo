<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Core\Controller;
use Abdyek\Whoo\Config\Controller as ControllerConfig;
use Abdyek\Whoo\Exception\InvalidDataException;
require 'propel/config.php';

class Example extends Controller {
    protected function run() {
    }
}

/**
 * @covers Controller::
 */

class ControllerTest extends TestCase {
    public function testConstructSuccess() {
        $data = [
            'strValue' => "example value",
            'numValue'=>123,
            'arrValue'=>['red', 'green', 'blue', 'purple'],
            'emailValue'=>'yunusemrebulut123@gmail.com',
            'boolValue'=>true
        ];
        $example = new Example($data);
        $this->assertSame($data, $example->data);
    }
    /**
     * @dataProvider dataProvider
     */
    public function testConstructInvalidDataException($data):void {
        $this->expectException(InvalidDataException::class);
        $example = new Example($data);
    }
    public function dataProvider() {
        return [
            [[
                'strValue' => '',
                'numValue'=>123,
                'arrValue'=>['red', 'green', 'blue', 'purple'],
                'emailValue'=>'yunusemrebulut123@gmail.com',
                'boolValue'=>true
            ]],
            [[
                'strValue' => "example value",
                'numValue'=>123,
                'arrValue'=>true,
                'emailValue'=>'yunusemrebulut123@gmail.com',
                'boolValue'=>false
            ]],
            [[
                'strValue' => "example value",
                'numValue'=>123,
                'arrValue'=>['red', 'green', 'blue', 'purple'],
                'emailValue'=>'yunusemrebulut123gmail.com',
                'boolValue'=>false
            ]],
            [[
                'strValue' => "example value",
                'numValue'=>false,
                'arrValue'=>['red', 'green', 'blue', 'purple'],
                'emailValue'=>'yunusemrebulut123@gmail.com',
                'boolValue'=>false
            ]],
            [[
                'strValue' => "example value",
                'numValue'=>false,
                'arrValue'=>['red', 'green', 'blue', 'purple'],
                'emailValue'=>'yunusemrebulut123@gmail.com',
                'boolValue'=>"value"
            ]]
        ];
    }
}
