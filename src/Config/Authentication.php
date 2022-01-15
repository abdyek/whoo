<?php

namespace Abdyek\Whoo\Config;

class Authentication {

    // TODO: remove this file and move the map to another appropriate place

    // mapping authentication type
    const TYPE_EMAIL_VERIFICATION = 0;
    const TYPE_MANAGE_2FA = 1;
    const TYPE_RESET_PW = 2;
    const TYPE_2FA = 3;

    // default value of authentication configurations
    const SIZE_OF_CODE_TO_VERIFY_EMAIL = 10;
    const SIZE_OF_CODE_TO_RESET_PW = 10;
    const SIZE_OF_CODE_TO_MANAGE_2FA = 6;
    const SIZE_OF_CODE_FOR_2FA = 5;
    const TRIAL_MAX_COUNT_TO_VERIFY_EMAIL = 3;
    const VALIDITY_TIME_TO_VERIFY_EMAIL = 180;
    const TRIAL_MAX_COUNT_TO_RESET_PW = 3;
    const VALIDITY_TIME_TO_RESET_PW = 180;
    const TRIAL_MAX_COUNT_TO_MANAGE_2FA = 3;
    const VALIDITY_TIME_TO_MANAGE_2FA = 180;
    const TRIAL_MAX_COUNT_TO_SIGN_IN_2FA = 3;
    const VALIDITY_TIME_TO_SIGN_IN_2FA = 180;

    static $SIZE_OF_CODE_TO_VERIFY_EMAIL = self::SIZE_OF_CODE_TO_VERIFY_EMAIL;
    static $SIZE_OF_CODE_TO_RESET_PW = self::SIZE_OF_CODE_TO_RESET_PW;
    static $SIZE_OF_CODE_TO_MANAGE_2FA = self::SIZE_OF_CODE_TO_MANAGE_2FA;
    static $SIZE_OF_CODE_FOR_2FA = self::SIZE_OF_CODE_FOR_2FA;
    static $TRIAL_MAX_COUNT_TO_VERIFY_EMAIL = self::TRIAL_MAX_COUNT_TO_VERIFY_EMAIL;
    static $VALIDITY_TIME_TO_VERIFY_EMAIL = self::VALIDITY_TIME_TO_VERIFY_EMAIL;
    static $TRIAL_MAX_COUNT_TO_RESET_PW = self::TRIAL_MAX_COUNT_TO_RESET_PW;
    static $VALIDITY_TIME_TO_RESET_PW = self::VALIDITY_TIME_TO_RESET_PW;
    static $TRIAL_MAX_COUNT_TO_MANAGE_2FA = self::TRIAL_MAX_COUNT_TO_MANAGE_2FA;
    static $VALIDITY_TIME_TO_MANAGE_2FA = self::VALIDITY_TIME_TO_MANAGE_2FA;
    static $TRIAL_MAX_COUNT_TO_SIGN_IN_2FA = self::TRIAL_MAX_COUNT_TO_SIGN_IN_2FA;
    static $VALIDITY_TIME_TO_SIGN_IN_2FA = self::VALIDITY_TIME_TO_SIGN_IN_2FA;
}
