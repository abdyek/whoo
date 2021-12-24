<?php

namespace Abdyek\Whoo\Core;

use Abdyek\Whoo\Core\Core;

class Config extends Core
{
    private array $requiredMap; 

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
    private int $validityTimeToVerifyEmaiL = 180;
    private int $trial_max_count_to_reset_pw = 3;
    private int $validityTimeToResetPw = 180;
    private int $trialMaxCountToManage2fa = 3;
    private int $validityTimeToManage2fa = 180;
    private int $trialMaxCountToSignIn2fa = 3;
    private int $validityTimeToSignIn2fa = 180;

    public function __construct() 
    {
        $this->requiredMap = require 'default/requiredMap.php';
    }

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

    public function getValidityTimeToVerifyEmaiL(): int
    {
        return $this->validityTimeToVerifyEmaiL;
    }

    public function setValidityTimeToVerifyEmaiL(int $validityTimeToVerifyEmaiL): void
    {
        $this->validityTimeToVerifyEmaiL = $validityTimeToVerifyEmaiL;
    }

    public function getTrial_max_count_to_reset_pw(): int
    {
        return $this->trial_max_count_to_reset_pw;
    }

    public function setTrial_max_count_to_reset_pw(int $trial_max_count_to_reset_pw): void
    {
        $this->trial_max_count_to_reset_pw = $trial_max_count_to_reset_pw;
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

}
