<?php

return [
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
    'SignIn'=>[
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
    'SignInByUsername'=>[
        'username'=>[
            'type'=>'str',
            'limits'=>[
                'min'=>1,
                'max'=>40
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
    'SetUsername'=>[
        'tempToken'=>[
            'type'=>'str',
            'limits'=>[
                'min'=>60,
                'max'=>60
            ]
        ],
        'username'=>[
            'type'=>'str',
            'limits'=>[
                'min'=>1,
                'max'=>40
            ]
        ]
    ],
    'SetAuthCodeForEmailVerification'=>[
        'email'=>[
            'type'=>'email',
            'limits'=>[
                'min'=>1,
                'max'=>255
            ]
        ]
    ],
    'VerifyEmail'=>[
        'email'=>[
            'type'=>'email',
            'limits'=>[
                'min'=>1,
                'max'=>255
            ]
        ],
        'authCode'=>[
            'type'=>'str',
            'limits'=>[
                'min'=>1,
                'max'=>10
            ]
        ]
    ],
    'SignOut'=>[
        'jwt'=>[
            'type'=>'str',
            'limits'=>[
                'min'=>1,
                'max'=>5000
            ]
        ]
    ],
    'SignInByProvider'=>[
        'email'=>[
            'type'=>'email',
            'limits'=>[
                'min'=>1,
                'max'=>255
            ]
        ],
        'provider'=>[
            'type'=>'str',
            'limits'=>[
                'min'=>1,
                'max'=>255
            ]
        ],
        'providerId'=>[
            'type'=>'str',
            'limits'=>[
                'min'=>1,
                'max'=>255
            ]
        ],
    ],
    'SignIn2FA'=>[
        'email'=>[
            'type'=>'email',
            'limits'=>[
                'min'=>1,
                'max'=>255
            ]
        ],
        'authCode'=>[
            'type'=>'str',
            'limits'=>[
                'min'=>1,
                'max'=>10
            ]
        ]
    ],
    'SignInByUsername2FA'=>[
        'username'=>[
            'type'=>'str',
            'limits'=>[
                'min'=>1,
                'max'=>40
            ]
        ],
        'authCode'=>[
            'type'=>'str',
            'limits'=>[
                'min'=>1,
                'max'=>10
            ]
        ]
    ],
    'SetAuthCodeToResetPassword'=>[
        'email'=>[
            'type'=>'email',
            'limits'=>[
                'min'=>1,
                'max'=>255
            ]
        ]
    ],
    'ResetPassword'=>[
        'email'=>[
            'type'=>'email',
            'limits'=>[
                'min'=>1,
                'max'=>255
            ]
        ],
        'newPassword'=>[
            'type'=>'str',
            'limits'=>[
                'min'=>8,
                'max'=>50
            ]
        ],
        'authCode'=>[
            'type'=>'str',
            'limits'=>[
                'min'=>1,
                'max'=>10
            ]
        ]
    ],
    'ChangeEmail'=>[
        'jwt'=>[
            'type'=>'str',
            'limits'=>[
                'min'=>1,
                'max'=>5000
            ]
        ],
        'newEmail'=>[
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
        ],
    ],
    'ChangeUsername'=> [
        'jwt'=>[
            'type'=>'str',
            'limits'=>[
                'min'=>1,
                'max'=>5000
            ]
        ],
        'newUsername'=>[
            'type'=>'str',
            'limits'=>[
                'min'=>1,
                'max'=>40
            ]
        ],
    ],
    'ChangePassword'=>[
        'jwt'=>[
            'type'=>'str',
            'limits'=>[
                'min'=>1,
                'max'=>5000
            ]
        ],
        'password'=>[
            'type'=>'str',
            'limits'=>[
                'min'=>8,
                'max'=>50
            ]
        ],
        'newPassword'=>[
            'type'=>'str',
            'limits'=>[
                'min'=>8,
                'max'=>50
            ]
        ]
    ],
    'SetAuthCodeToManage2FA'=>[
        'jwt'=>[
            'type'=>'str',
            'limits'=>[
                'min'=>1,
                'max'=>5000
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
    'Manage2FA'=>[
        'jwt'=>[
            'type'=>'str',
            'limits'=>[
                'min'=>1,
                'max'=>5000
            ]
        ],
        'authCode'=>[
            'type'=>'str',
            'limits'=>[
                'min'=>1,
                'max'=>10
            ]
        ],
        'open'=>[
            'type'=>'bool'
        ]
    ],
    'Delete'=>[
        'jwt'=>[
            'type'=>'str',
            'limits'=>[
                'min'=>1,
                'max'=>5000
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
    'FetchInfo'=> [
        'jwt'=>[
            'type'=>'str',
            'limits'=>[
                'min'=>1,
                'max'=>5000
            ]
        ]
    ]
];
