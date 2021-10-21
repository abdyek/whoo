<?php

namespace Abdyek\Whoo\Exception;
use Abdyek\Whoo\Tool\TemporaryToken;

class NullUsernameException extends \Exception {
    public $tempToken = null;
    public function generateTempToken($user) {
        $tempToken = TemporaryToken::generate($user->getId());
        $this->tempToken = $tempToken;
    }
}
