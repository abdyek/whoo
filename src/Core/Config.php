<?php

namespace Abdyek\Whoo\Core;

use Abdyek\Whoo\Core\Core;

class Config extends Core
{
    private array $requiredMap = [
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
            ],
            'passwordAgain'=>[
                'type'=>'str',
                'limits'=>[
                    'min'=>8,
                    'max'=>50
                ],
                'optional' => true
            ],
            'username'=>[
                'type'=>'str',
                'limits'=>[
                    'min'=>1,
                    'max'=>40
                ],
                'optional' => true
            ],
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
            ],
            'passwordAgain' => [
                'type' => 'str',
                'limits' => [
                    'min' => 8,
                    'max' => 50
                ],
                'optional' => true
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
            ],
            'passwordAgain'=>[
                'type'=>'str',
                'limits'=>[
                    'min'=>8,
                    'max'=>50
                ],
                'optional' => true
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
    ];

    // src/Config/Whoo.php
    private bool $denyIfNotVerifiedToSignIn = true;
    private bool $denyIfNotVerifiedToResetPw = true;
    private bool $useUsername = true;
    private bool $denyIfNotSetUsername = true;
    private bool $denyIfSignUpBeforeByEmail = false;
    private bool $default2fa = false;

    // src/Config/Authentication.php
    private int $sizeOfCodeToVerifyEmail = 10;
    private int $sizeOfCodeToResetPw = 10;
    private int $sizeOfCodeToManage2fa = 6;
    private int $sizeOfCodeFor2fa = 5;
    private int $trialMaxCountToVerifyEmail = 3;
    private int $validityTimeToVerifyEmail = 180;
    private int $trialMaxCountToResetPw = 3;
    private int $validityTimeToResetPw = 180;
    private int $trialMaxCountToManage2fa = 3;
    private int $validityTimeToManage2fa = 180;
    private int $trialMaxCountToSignIn2fa = 3;
    private int $validityTimeToSignIn2fa = 180;

    // other
    private string $secretKey = 's3cr3t';

    public function getRequiredMap(): array
    {
        return $this->requiredMap;
    }

    public function setRequiredMap(array $requiredMap): void
    {
        $this->requiredMap = $requiredMap;
    }

    public function getDenyIfNotVerifiedToSignIn(): bool
    {
        return $this->denyIfNotVerifiedToSignIn;
    }

    public function setDenyIfNotVerifiedToSignIn(bool $denyIfNotVerifiedToSignIn): void
    {
        $this->denyIfNotVerifiedToSignIn = $denyIfNotVerifiedToSignIn;
    }

    public function getDenyIfNotVerifiedToResetPw(): bool
    {
        return $this->denyIfNotVerifiedToResetPw;
    }

    public function setDenyIfNotVerifiedToResetPw(bool $denyIfNotVerifiedToResetPw): void
    {
        $this->denyIfNotVerifiedToResetPw = $denyIfNotVerifiedToResetPw;
    }

    public function getUseUsername(): bool
    {
        return $this->useUsername;
    }

    public function setUseUsername(bool $useUsername): void
    {
        $this->useUsername = $useUsername;
    }

    public function getDenyIfNotSetUsername(): bool
    {
        return $this->denyIfNotSetUsername;
    }

    public function setDenyIfNotSetUsername(bool $denyIfNotSetUsername): void
    {
        $this->denyIfNotSetUsername = $denyIfNotSetUsername;
    }

    public function getDenyIfSignUpBeforeByEmail(): bool
    {
        return $this->denyIfSignUpBeforeByEmail;
    }

    public function setDenyIfSignUpBeforeByEmail(bool $denyIfSignUpBeforeByEmail): void
    {
        $this->denyIfSignUpBeforeByEmail = $denyIfSignUpBeforeByEmail;
    }

    public function getDefault2fa(): bool
    {
        return $this->default2fa;
    }

    public function setDefault2fa(bool $default2fa): void
    {
        $this->default2fa = $default2fa;
    }

    public function getSizeOfCodeToVerifyEmail(): int
    {
        return $this->sizeOfCodeToVerifyEmail;
    }

    public function setSizeOfCodeToVerifyEmail(int $sizeOfCodeToVerifyEmail): void
    {
        $this->sizeOfCodeToVerifyEmail = $sizeOfCodeToVerifyEmail;
    }

    public function getSizeOfCodeToResetPw(): int
    {
        return $this->sizeOfCodeToResetPw;
    }

    public function setSizeOfCodeToResetPw(int $sizeOfCodeToResetPw): void
    {
        $this->sizeOfCodeToResetPw = $sizeOfCodeToResetPw;
    }

    public function getSizeOfCodeToManage2fa(): int
    {
        return $this->sizeOfCodeToManage2fa;
    }

    public function setSizeOfCodeToManage2fa(int $sizeOfCodeToManage2fa): void
    {
        $this->sizeOfCodeToManage2fa = $sizeOfCodeToManage2fa;
    }

    public function getSizeOfCodeFor2fa(): int
    {
        return $this->sizeOfCodeFor2fa;
    }

    public function setSizeOfCodeFor2fa(int $sizeOfCodeFor2fa): void
    {
        $this->sizeOfCodeFor2fa = $sizeOfCodeFor2fa;
    }

    public function getTrialMaxCountToVerifyEmail(): int
    {
        return $this->trialMaxCountToVerifyEmail;
    }

    public function setTrialMaxCountToVerifyEmail(int $trialMaxCountToVerifyEmail): void
    {
        $this->trialMaxCountToVerifyEmail = $trialMaxCountToVerifyEmail;
    }

    public function getValidityTimeToVerifyEmail(): int
    {
        return $this->validityTimeToVerifyEmail;
    }

    public function setValidityTimeToVerifyEmail(int $validityTimeToVerifyEmail): void
    {
        $this->validityTimeToVerifyEmail = $validityTimeToVerifyEmail;
    }

    public function getTrialMaxCountToResetPw(): int
    {
        return $this->trialMaxCountToResetPw;
    }

    public function setTrialMaxCountToResetPw(int $trialMaxCountToResetPw): void
    {
        $this->trialMaxCountToResetPw = $trialMaxCountToResetPw;
    }

    public function getValidityTimeToResetPw(): int
    {
        return $this->validityTimeToResetPw;
    }

    public function setValidityTimeToResetPw(int $validityTimeToResetPw): void
    {
        $this->validityTimeToResetPw = $validityTimeToResetPw;
    }

    public function getTrialMaxCountToManage2fa(): int
    {
        return $this->trialMaxCountToManage2fa;
    }

    public function setTrialMaxCountToManage2fa(int $trialMaxCountToManage2fa): void
    {
        $this->trialMaxCountToManage2fa = $trialMaxCountToManage2fa;
    }

    public function getValidityTimeToManage2fa(): int
    {
        return $this->validityTimeToManage2fa;
    }

    public function setValidityTimeToManage2fa(int $validityTimeToManage2fa): void
    {
        $this->validityTimeToManage2fa = $validityTimeToManage2fa;
    }

    public function getTrialMaxCountToSignIn2fa(): int
    {
        return $this->trialMaxCountToSignIn2fa;
    }

    public function setTrialMaxCountToSignIn2fa(int $trialMaxCountToSignIn2fa): void
    {
        $this->trialMaxCountToSignIn2fa = $trialMaxCountToSignIn2fa;
    }

    public function getValidityTimeToSignIn2fa(): int
    {
        return $this->validityTimeToSignIn2fa;
    }

    public function setValidityTimeToSignIn2fa(int $validityTimeToSignIn2fa): void
    {
        $this->validityTimeToSignIn2fa = $validityTimeToSignIn2fa;
    }

    public function getSecretKey(): string
    {
        return $this->secretKey;
    }

    public function setSecretKey(string $secretKey): void
    {
        $this->secretKey = $secretKey;
    }

}
