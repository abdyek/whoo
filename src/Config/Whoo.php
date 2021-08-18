<?php

namespace Whoo\Config;

class Whoo {
    const CONFIG = [
        'DENY_IF_NOT_VERIFIED_TO_SIGN_IN'=>true,
        'DENY_IF_NOT_VERIFIED_TO_RESET_PW'=>true, // I will use it for ResetPassword controller class
        'USE_USERNAME'=>true,
        'DENY_IF_NOT_SET_USERNAME'=>true,         // I will use it later
        'DENY_IF_SIGN_UP_BEFORE_BY_EMAIL'=>false,
        'REAL_STATELESS'=>false,
    ];
}
