<?php

namespace Abdyek\Whoo\Config;

class Whoo {
    const CONFIG_FILE = 'whoo/config.php';
    const DENY_IF_NOT_VERIFIED_TO_SIGN_IN = true;
    const DENY_IF_NOT_VERIFIED_TO_RESET_PW = true;
    const USE_USERNAME = true;
    const DENY_IF_NOT_SET_USERNAME = true;
    const DENY_IF_SIGN_UP_BEFORE_BY_EMAIL = false;
    const DEFAULT_2FA = false;

    static $CONFIG_FILE = self::CONFIG_FILE;
    static $DENY_IF_NOT_VERIFIED_TO_SIGN_IN = self::DENY_IF_NOT_VERIFIED_TO_SIGN_IN;
    static $DENY_IF_NOT_VERIFIED_TO_RESET_PW = self::DENY_IF_NOT_VERIFIED_TO_RESET_PW;
    static $USE_USERNAME = self::USE_USERNAME;
    static $DENY_IF_NOT_SET_USERNAME = self::DENY_IF_NOT_SET_USERNAME;
    static $DENY_IF_SIGN_UP_BEFORE_BY_EMAIL = self::DENY_IF_SIGN_UP_BEFORE_BY_EMAIL;
    static $DEFAULT_2FA = self::DEFAULT_2FA;
}
