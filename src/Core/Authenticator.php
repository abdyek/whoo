<?php

namespace Abdyek\Whoo\Core;

use Abdyek\Whoo\Core\Core;
use Abdyek\Whoo\Tool\JWT;

class Authenticator extends Core
{
    private $user = null;

    public function check(): void
    {
        $data = $this->controller->getData();
        // I will make it dynamic
        if(isset($data['jwt'])) {
            $this->user = JWT::getPayloadWithUser($data['jwt'])['user'];
        }
    }

    public function getUser()
    {
        return $this->user;
    }
}
