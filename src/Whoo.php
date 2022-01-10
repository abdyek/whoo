<?php

namespace Abdyek\Whoo;

use Abdyek\Whoo\Core\AbstractController;
use Abdyek\Whoo\Config\Propel;
use Abdyek\Whoo\Core\Config;
use Abdyek\Whoo\Core\Data;

class Whoo
{
    public bool $success = true;
    private AbstractController $controller;
    private static ?Config $config = null;
    private array $callbacks = [];
    public array $content = [];

    public function __construct(string $controller, ?array $args = [])
    {
        $class = 'Abdyek\\Whoo\\Controller\\' . $controller;
        $config = (self::$config !== null ? clone(self::$config) : null);
        $this->controller = new $class(new Data($args), $config);
    }

    public function catchException(string $exception, object $callback)
    {
        $this->callbacks[$exception] = $callback;
        return $this;
    }

    public function run(): void
    {
        try {
            $this->controller->triggerRun();
            $this->content = $this->controller->getResponse()->getContent();
        } catch(\Exception $e) {
            $this->success = false;
            $this->exception = $e;
            $pieces = explode('\\', get_class($e));
            $pieces = explode('Exception', end($pieces));
            if(!isset($this->callbacks[$pieces[0]])) {
                throw $e;
            }
            ($this->callbacks[$pieces[0]])();
        }
    }

    public static function loadPropelConfig(): void
    {
        require Propel::$CONFIG_FILE;
    }

    public static function getConfig(): ?Config
    {
        return self::$config;
    }

    public static function setConfig(Config $config): void
    {
        self::$config = $config;
    }

    public function getController(): ? AbstractController
    {
        return $this->controller;
    }

    /*
    public function setJWTAdditionalClaims(array $claims): void
    {
        $this->controller->getConfig()->setJWTPayload($claims);
    }
     */

}
