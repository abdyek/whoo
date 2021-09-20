<?php

namespace Abdyek\Whoo\Config;

class Authentication {
    static $SIZE_OF_CODE_FOR_EMAIL_VER = 10;
    static $SIZE_OF_CODE_TO_RESET_PW = 10;
    static $SIZE_OF_CODE_TO_MANAGE_2FA = 6;
    static $SIZE_OF_CODE_FOR_2FA = 5;
    static $TRIAL_MAX_COUNT = 3;
    static $VALIDITY_TIME = 180;
    static $TRIAL_MAX_COUNT_TO_RESET_PW = 3;
    static $VALIDITY_TIME_TO_RESET_PW = 180;
    static $TRIAL_MAX_COUNT_TO_MANAGE_2FA = 3;
    static $VALIDITY_TIME_TO_MANAGE_2FA = 180;
    static $TRIAL_MAX_COUNT_TO_SIGN_IN_2FA = 3;
    static $VALIDITY_TIME_TO_SIGN_IN_2FA = 180;
    static $TYPE_EMAIL_VERIFICATION = 0;
    static $TYPE_MANAGE_2FA = 1;
    static $TYPE_RESET_PW = 2;
    static $TYPE_2FA = 3;
}
