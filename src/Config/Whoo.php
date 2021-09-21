<?php

namespace Abdyek\Whoo\Config;

class Whoo {
    static $CONFIG_FILE = 'whoo/config.php';
    static $DENY_IF_NOT_VERIFIED_TO_SIGN_IN = true;
    static $DENY_IF_NOT_VERIFIED_TO_RESET_PW = true;
    static $USE_USERNAME = true;
    static $DENY_IF_NOT_SET_USERNAME = true;
    static $DENY_IF_SIGN_UP_BEFORE_BY_EMAIL = false;
    static $DEFAULT_2FA = false;
}
