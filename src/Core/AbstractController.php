<?php

namespace Abdyek\Whoo\Core;

use Abdyek\Whoo\Core\Data;
use Abdyek\Whoo\Core\Config;
use Abdyek\Whoo\Core\Validator;

abstract class AbstractController
{
    protected Data $data;
    protected Config $config;
    private Validator $validator;
    private \DateTime $dateTime;

    abstract public function run();

    public function __construct(Data $data, ?Config $config, ?Validator $validator, ?\DateTime $dateTime)
    {
        $this->data = $data;
        $this->config = $config ?? new Config;
        $this->validator = $validator ?? new Validator;
        $this->dateTime = $dateTime ?? new \DateTime;
        $this->setThis();
    }

    private function setThis(): void
    {
        $this->data->setController($this);
        $this->config->setController($this);
        $this->validator->setController($this);
    }

    public function triggerRun(): void
    {
        // User sınıfı oluşturulacak
        $this->user->checkPermission();
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
        return $this->data();
    }

    public function setData(Data $data): void
    {
        $this->data = $data;
        $data->setController($this);
    }

    public function getConfig(): Config
    {
        return $this->config();
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

}
