<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Core\Config;
use Abdyek\Whoo\Core\Data;
use Abdyek\Whoo\Core\Validator;
use Abdyek\Whoo\Exception\InvalidDataException;
use Pseudo\ExampleController;

/**
 * @covers Validator::
 */

class ValidatorTest extends TestCase
{
    /**
     * @dataProvider providerForSuccess
     */
    public function test($pattern, $content)
    {
        $validator = new Validator();
        $config = new Config();
        $config->setRequiredMap([
            'ExampleController' => $pattern
        ]);
        $controller = new ExampleController(new Data($content), $config, $validator);
        $controller->triggerRun();
        $this->assertTrue(true);
    }

    public function providerForSuccess()
    {
        return [
            [
                [
                    'username' => [
                        'type' => 'str', // str, arr, int, email, bool, num   
                        'limits' => [
                            'min' => 1,
                            'max' => 10
                        ]
                    ],
                    'email' => [
                        'type' => 'email',
                        'limits' => [
                            'min' => 0,
                            'max' => 255
                        ]
                    ]
                ],
                [
                    'username' => 'yunusEmre',
                    'email' => 'yunusemrebulut123@gmail.com'
                ]
            ],
            [
                [
                    'password' => [
                        'type' => 'str',
                        'limits' => [
                            'min' => 1,
                            'max' => 10
                        ]
                    ],
                    'id' => [
                        'type' => 'int',
                        'limits' => [
                            'min' => 1,
                            'max' => 11
                        ]
                    ],
                    'dontForgetMe' => [
                        'type' => 'bool'
                    ],
                    'phone' => [
                        'type' => 'num',
                        'limits' => [
                            'min' => 9,
                            'max' => 9
                        ]
                    ]
                ],
                [
                    'password' => '123456',
                    'id' => 13,
                    'dontForgetMe' => false,
                    'phone' => '123456789'
                ]
            ]
        ];
    }

    /**
     * @dataProvider providerForInvalid
     */
    public function testInvalidDataException($pattern, $content)
    {
        $this->expectException(InvalidDataException::class);
        $validator = new Validator();
        $config = new Config();
        $config->setRequiredMap([
            'ExampleController' => $pattern
        ]);
        $controller = new ExampleController(new Data($content), $config, $validator);
        $controller->triggerRun();
        
    }

    public function providerForInvalid()
    {
        return [
            [
                [
                    'username' => [
                        'type' => 'str',
                        'limits' => [
                            'min' => 1,
                            'max' => 10
                        ]
                    ],
                    'email' => [
                        'type' => 'email',
                        'limits' => [
                            'min' => 0,
                            'max' => 255
                        ]
                    ]
                ],
                [
                    // different type
                    'username' => 123,
                    'email' => 'yunusemrebulut123@gmail.com'
                ]
            ],
            [
                [
                    'username' => [
                        'type' => 'str',
                        'limits' => [
                            'min' => 5,
                            'max' => 15
                        ]
                    ]
                ],
                [
                    // under length of min
                    'username' => 'amy'
                ]
            ],
            [
                [
                    'dontForgetMe' => [
                        'type' => 'bool',
                    ]
                ],
                [ 
                    // there isn't 'dontForgetMe' in the data
                ]
            ]
        ];
    }
}
