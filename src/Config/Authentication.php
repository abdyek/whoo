<?php

namespace Whoo\Config;

class Authentication {
    const SIZE_OF_CODE_FOR_EMAIL_VER = 10;
    const SIZE_OF_CODE_TO_RESET_PW = 10;
    const SIZE_OF_CODE_FOR_MANAGE_2FA = 6;
    const SIZE_OF_CODE_FOR_2FA = 5;
    const TRIAL_MAX_COUNT = 3;
    const VALIDITY_TIME = 180;
    const TRIAL_MAX_COUNT_TO_RESET_PW = 3;
    const VALIDITY_TIME_TO_RESET_PW = 180;
    const TRIAL_MAX_COUNT_TO_MANAGE_2FA = 3;
    const VALIDITY_TIME_TO_MANAGE_2FA = 180;
    const TRIAL_MAX_COUNT_TO_SIGN_IN_2FA = 3;
    const VALIDITY_TIME_TO_SIGN_IN_2FA = 180;
}
