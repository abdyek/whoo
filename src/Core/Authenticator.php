<?php

namespace Abdyek\Whoo\Core;

use Abdyek\Whoo\Core\Core;
use Abdyek\Whoo\Tool\JWT;

class Authenticator extends Core
{
    private $user = null;
    private $jwt = null;

    public function check(): void
    {
        $data = $this->controller->getData();
        $content = $data->getContent();
        // I will make it dynamic
        if(isset($content['jwt'])) {
            $this->jwt = $content['jwt'];
            $this->user = JWT::getPayloadWithUser($content['jwt'])['user'];
        }
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getJwt(): string
    {
        return $this->jwt;
    }

    public function setJwt(string $jwt): void
    {
        $this->jwt = $jwt;
    }
}
