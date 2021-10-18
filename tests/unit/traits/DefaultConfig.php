<?php

trait DefaultConfig {
    public static function setDefaultConfig() {
        Abdyek\Whoo\Config\Whoo::$CONFIG_FILE = Abdyek\Whoo\Config\Whoo::CONFIG_FILE;
        Abdyek\Whoo\Config\Whoo::$DENY_IF_NOT_VERIFIED_TO_SIGN_IN = Abdyek\Whoo\Config\Whoo::DENY_IF_NOT_VERIFIED_TO_SIGN_IN;
        Abdyek\Whoo\Config\Whoo::$DENY_IF_NOT_VERIFIED_TO_RESET_PW = Abdyek\Whoo\Config\Whoo::DENY_IF_NOT_VERIFIED_TO_RESET_PW;
        Abdyek\Whoo\Config\Whoo::$USE_USERNAME = Abdyek\Whoo\Config\Whoo::USE_USERNAME;
        Abdyek\Whoo\Config\Whoo::$DENY_IF_NOT_SET_USERNAME = Abdyek\Whoo\Config\Whoo::DENY_IF_NOT_SET_USERNAME;
        Abdyek\Whoo\Config\Whoo::$DENY_IF_SIGN_UP_BEFORE_BY_EMAIL = Abdyek\Whoo\Config\Whoo::DENY_IF_SIGN_UP_BEFORE_BY_EMAIL;
        Abdyek\Whoo\Config\Whoo::$DEFAULT_2FA = Abdyek\Whoo\Config\Whoo::DEFAULT_2FA;
        Abdyek\Whoo\Config\Authentication::$SIZE_OF_CODE_TO_VERIFY_EMAIL = Abdyek\Whoo\Config\Authentication::SIZE_OF_CODE_TO_VERIFY_EMAIL;
        Abdyek\Whoo\Config\Authentication::$SIZE_OF_CODE_TO_RESET_PW = Abdyek\Whoo\Config\Authentication::SIZE_OF_CODE_TO_RESET_PW;
        Abdyek\Whoo\Config\Authentication::$SIZE_OF_CODE_TO_MANAGE_2FA = Abdyek\Whoo\Config\Authentication::SIZE_OF_CODE_TO_MANAGE_2FA;
        Abdyek\Whoo\Config\Authentication::$SIZE_OF_CODE_FOR_2FA = Abdyek\Whoo\Config\Authentication::SIZE_OF_CODE_FOR_2FA;
        Abdyek\Whoo\Config\Authentication::$TRIAL_MAX_COUNT_TO_VERIFY_EMAIL = Abdyek\Whoo\Config\Authentication::TRIAL_MAX_COUNT_TO_VERIFY_EMAIL;
        Abdyek\Whoo\Config\Authentication::$VALIDITY_TIME_TO_VERIFY_EMAIL = Abdyek\Whoo\Config\Authentication::VALIDITY_TIME_TO_VERIFY_EMAIL;
        Abdyek\Whoo\Config\Authentication::$TRIAL_MAX_COUNT_TO_RESET_PW = Abdyek\Whoo\Config\Authentication::TRIAL_MAX_COUNT_TO_RESET_PW;
        Abdyek\Whoo\Config\Authentication::$VALIDITY_TIME_TO_RESET_PW = Abdyek\Whoo\Config\Authentication::VALIDITY_TIME_TO_RESET_PW;
        Abdyek\Whoo\Config\Authentication::$TRIAL_MAX_COUNT_TO_MANAGE_2FA = Abdyek\Whoo\Config\Authentication::TRIAL_MAX_COUNT_TO_MANAGE_2FA;
        Abdyek\Whoo\Config\Authentication::$VALIDITY_TIME_TO_MANAGE_2FA = Abdyek\Whoo\Config\Authentication::VALIDITY_TIME_TO_MANAGE_2FA;
        Abdyek\Whoo\Config\Authentication::$TRIAL_MAX_COUNT_TO_SIGN_IN_2FA = Abdyek\Whoo\Config\Authentication::TRIAL_MAX_COUNT_TO_SIGN_IN_2FA;
        Abdyek\Whoo\Config\Authentication::$VALIDITY_TIME_TO_SIGN_IN_2FA = Abdyek\Whoo\Config\Authentication::VALIDITY_TIME_TO_SIGN_IN_2FA;
    }
}
