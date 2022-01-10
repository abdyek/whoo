<?php

namespace Abdyek\Whoo\Core;

use Abdyek\Whoo\Core\Data;
use Abdyek\Whoo\Core\Config;
use Abdyek\Whoo\Core\Validator;
use Abdyek\Whoo\Core\Authenticator;
use Abdyek\Whoo\Core\Response;
use Abdyek\Whoo\Tool\JWT;

abstract class AbstractController
{
    protected Data $data;
    protected Config $config;
    protected Validator $validator;
    protected \DateTime $dateTime;
    protected Authenticator $authenticator;
    protected Response $response;

    abstract public function run(): void;

    // TODO: add a service container library to manage them.
    public function __construct(?Data $data = null, ?Config $config = null, ?Validator $validator = null, ?\DateTime $dateTime = null, ?Authenticator $authenticator = null, ?Response $response = null)
    {
        $this->data = $data ?? new Data();
        $this->config = $config ?? new Config;
        $this->validator = $validator ?? new Validator;
        $this->dateTime = $dateTime ?? new \DateTime;
        $this->authenticator = $authenticator ?? new Authenticator(new JWT);
        $this->response = $response ?? new Response;
        $this->setThis();
    }

    private function setThis(): void
    {
        $this->data->setController($this);
        $this->config->setController($this);
        $this->validator->setController($this);
        $this->authenticator->setController($this);
        $this->response->setController($this);
    }

    public function triggerRun(): void
    {
        $this->authenticator->check();
        $this->validator->validate();
        $this->run();
    }

    public function getClassName(): string
    {
        $piece = explode('\\', get_class($this));
        return end($piece);
    }

    // get & set
    
    public function getData(): Data
    {
        return $this->data;
    }

    public function setData(Data $data): void
    {
        $this->data = $data;
        $data->setController($this);
    }

    public function getConfig(): Config
    {
        return $this->config;
    }

    public function setConfig(Config $config): void
    {
        $this->config = $config;
        $config->setController($this);
    }

    public function getValidator(): Validator
    {
        return $this->validator;
    }

    public function setValidator(Validator $validator): void
    {
        $this->validator = $validator;
        $validator->setController($this);
    }

    public function getDateTime(): \DateTime
    {
        return $this->dateTime;
    }

    public function setDateTime(\DateTime $dateTime): void
    {
        $this->dateTime = $dateTime;
    }

    public function getAuthenticator(): Authenticator
    {
        return $this->authenticator;
    }

    public function setAuthenticator(Authenticator $authenticator): void
    {
        $this->authenticator = $authenticator;
        $authenticator->setController($this);
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function setResponse(Response $response): void
    {
        $this->response = $response;
        $response->setController($this);
    }
}
