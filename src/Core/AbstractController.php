<?php

namespace Abdyek\Whoo\Core;

use Abdyek\Whoo\Core\Data;
use Abdyek\Whoo\Core\Config;
use Abdyek\Whoo\Core\Validator;
use Abdyek\Whoo\Core\Authenticator;

abstract class AbstractController
{
    protected array $data;
    protected Config $config;
    protected Validator $validator;
    protected \DateTime $dateTime;
    protected Authenticator $authenticator;

    abstract public function run(): void;

    public function __construct(?array $data = [], ?Config $config = null, ?Validator $validator = null, ?\DateTime $dateTime = null, ?Authenticator $authenticator = null)
    {
        $this->data = $data;
        $this->config = $config ?? new Config;
        $this->validator = $validator ?? new Validator;
        $this->dateTime = $dateTime ?? new \DateTime;
        $this->authenticator = $authenticator ?? new Authenticator;
        $this->setThis();
    }

    private function setThis(): void
    {
        $this->config->setController($this);
        $this->validator->setController($this);
        $this->authenticator->setController($this);
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
    
    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
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

}
