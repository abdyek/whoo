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
        Abdyek\Whoo\Config\Authentication::$SIZE_OF_CODE_FOR_EMAIL_VER = Abdyek\Whoo\Config\Authentication::SIZE_OF_CODE_FOR_EMAIL_VER;
        Abdyek\Whoo\Config\Authentication::$SIZE_OF_CODE_TO_RESET_PW = Abdyek\Whoo\Config\Authentication::SIZE_OF_CODE_TO_RESET_PW;
        Abdyek\Whoo\Config\Authentication::$SIZE_OF_CODE_TO_MANAGE_2FA = Abdyek\Whoo\Config\Authentication::SIZE_OF_CODE_TO_MANAGE_2FA;
        Abdyek\Whoo\Config\Authentication::$SIZE_OF_CODE_FOR_2FA = Abdyek\Whoo\Config\Authentication::SIZE_OF_CODE_FOR_2FA;
        Abdyek\Whoo\Config\Authentication::$TRIAL_MAX_COUNT = Abdyek\Whoo\Config\Authentication::TRIAL_MAX_COUNT;
        Abdyek\Whoo\Config\Authentication::$VALIDITY_TIME = Abdyek\Whoo\Config\Authentication::VALIDITY_TIME;
        Abdyek\Whoo\Config\Authentication::$TRIAL_MAX_COUNT_TO_RESET_PW = Abdyek\Whoo\Config\Authentication::TRIAL_MAX_COUNT_TO_RESET_PW;
        Abdyek\Whoo\Config\Authentication::$VALIDITY_TIME_TO_RESET_PW = Abdyek\Whoo\Config\Authentication::VALIDITY_TIME_TO_RESET_PW;
        Abdyek\Whoo\Config\Authentication::$TRIAL_MAX_COUNT_TO_MANAGE_2FA = Abdyek\Whoo\Config\Authentication::TRIAL_MAX_COUNT_TO_MANAGE_2FA;
        Abdyek\Whoo\Config\Authentication::$VALIDITY_TIME_TO_MANAGE_2FA = Abdyek\Whoo\Config\Authentication::VALIDITY_TIME_TO_MANAGE_2FA;
        Abdyek\Whoo\Config\Authentication::$TRIAL_MAX_COUNT_TO_SIGN_IN_2FA = Abdyek\Whoo\Config\Authentication::TRIAL_MAX_COUNT_TO_SIGN_IN_2FA;
        Abdyek\Whoo\Config\Authentication::$VALIDITY_TIME_TO_SIGN_IN_2FA = Abdyek\Whoo\Config\Authentication::VALIDITY_TIME_TO_SIGN_IN_2FA;
        Abdyek\Whoo\Config\Authentication::$TYPE_EMAIL_VERIFICATION = Abdyek\Whoo\Config\Authentication::TYPE_EMAIL_VERIFICATION;
        Abdyek\Whoo\Config\Authentication::$TYPE_MANAGE_2FA = Abdyek\Whoo\Config\Authentication::TYPE_MANAGE_2FA;
        Abdyek\Whoo\Config\Authentication::$TYPE_RESET_PW = Abdyek\Whoo\Config\Authentication::TYPE_RESET_PW;
        Abdyek\Whoo\Config\Authentication::$TYPE_2FA = Abdyek\Whoo\Config\Authentication::TYPE_2FA;
    }
}
