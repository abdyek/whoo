<?php

namespace Whoo\Config;

class Controller {
    const REQUIRED = [
        // this is example to test
        'Example'=>[
            'strValue'=>[
                'type'=>'str',
                'limits'=>[
                    'min'=>10,
                    'max'=>50
                ]
            ],
            'numValue'=>[
                'type'=>'num',
                'limits'=>[
                    'min'=>1,
                    'max'=>10
                ]
            ],
            'arrValue'=>[
                'type'=>'arr',
                'limits'=>[
                    'min'=>2,
                    'max'=>5
                ]
            ],
            'emailValue'=>[
                'type'=>'email',
                'limits'=>[
                    'min'=>1,
                    'max'=>255
                ]
            ],
            'boolValue'=>[
                'type'=>'bool'
            ]
        ],
        // ^ this is exampe to test
        'SignUp'=>[
            'email'=>[
                'type'=>'email',
                'limits'=>[
                    'min'=>1,
                    'max'=>255
                ]
            ],
            'password'=>[
                'type'=>'str',
                'limits'=>[
                    'min'=>8,
                    'max'=>50
                ]
            ]
        ],
    ];
}
