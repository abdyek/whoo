<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Core\Controller;
use Abdyek\Whoo\Config\Controller as ControllerConfig;
use Abdyek\Whoo\Tool\Config;
use Abdyek\Whoo\Exception\InvalidDataException;

class Example extends Controller {
    protected function run() {
    }
}

/**
 * @covers Controller::
 */

class ControllerTest extends TestCase {
    public static function setUpBeforeClass(): void {
        Config::setPropelConfigDir('propel/config.php');
    }
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
    /*
     * I will fill it after. I want to test the controller class correctly but the controller class doesn't have dynamic REQUIRED config.
     * So if I fill this test function, I had to fill unnecessary values in Config\Controller::REQUIRED. I think controller classes tests are enought for product.
    public function testIsThereOptional() {

    }
    */
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
