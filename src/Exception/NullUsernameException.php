<?php

namespace Abdyek\Whoo\Exception;
use Abdyek\Whoo\Tool\TemporaryToken;

class NullUsernameException extends \Exception
{
    public $tempToken = null;
    public function generateTempToken($user, string $secretKey) {
        $tempToken = TemporaryToken::generate($user->getId(), $secretKey);
        $this->tempToken = $tempToken;
    }
}
