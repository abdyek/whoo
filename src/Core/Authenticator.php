<?php

namespace Abdyek\Whoo\Core;

use Abdyek\Whoo\Core\Core;
use Abdyek\Whoo\Tool\JWT;
use Abdyek\Whoo\Tool\Interfaces\JWTInterface;
use Abdyek\Whoo\Repository\User;
use Abdyek\Whoo\Exception\InvalidTokenException;

class Authenticator extends Core
{
    private $user = null;
    private $jwt = null;
    private JWTInterface $JWTObject;

    public function __construct(JWTInterface $JWTObject)
    {
        $this->JWTObject = $JWTObject;
    }

    public function check(): void
    {
        $data = $this->controller->getData();
        $content = $data->getContent();
        // TODO: make it dynamic
        if(isset($content['jwt'])) {
            $this->jwt = $content['jwt'];
            $payload = $this->JWTObject->payload($content['jwt']);
            $this->user = User::getById($payload['whoo']->userId);
            if($this->user->getSignOutCount() > $payload['whoo']->signOutCount) {
                throw new InvalidTokenException;
            }
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

    public function getJWTObject(): JWTInterface
    {
        return $this->JWTObject;
    }

    public function setJWTObject(JWTInterface $JWTObject): void
    {
        $this->JWTObject = $JWTObject;
    }

}
