<?php

namespace Abdyek\Whoo;

use Abdyek\Whoo\Core\AbstractController;
use Abdyek\Whoo\Core\Config;
use Abdyek\Whoo\Core\Data;

class Whoo
{
    public bool $success = true;
    private AbstractController $controller;
    private static ?Config $globalConfig = null;
    private array $callbacks = [];
    private object $successCallback;

    public function __construct(string $controller, ?array $args = [])
    {
        $this->successCallback = function() {};
        $class = 'Abdyek\\Whoo\\Controller\\' . $controller;
        $config = (self::$globalConfig !== null ? clone(self::$globalConfig) : null);
        $this->controller = new $class(new Data($args), $config);
    }

    public function success(object $callback)
    {
        $this->successCallback = $callback;
        return $this;
    }

    public function exception(string $exception, object $callback)
    {
        $this->callbacks[$exception] = $callback;
        return $this;
    }

    public function run(): void
    {
        try {
            $this->controller->triggerRun();
            ($this->successCallback)($this->controller->getResponse()->getContent());
        } catch(\Exception $e) {
            $this->success = false;
            $this->exception = $e;
            $pieces = explode('\\', get_class($e));
            $pieces = explode('Exception', end($pieces));
            if(!isset($this->callbacks[$pieces[0]])) {
                throw $e;
            }
            ($this->callbacks[$pieces[0]])($e);
        }
    }

    public static function loadPropelConfig(): void
    {
        require 'whoo/propel/config.php';
    }

    public static function getGlobalConfig(): ?Config
    {
        return self::$globalConfig;
    }

    public static function setGlobalConfig(Config $globalConfig): void
    {
        self::$globalConfig = $globalConfig;
    }

    public function getConfig(): Config
    {
        return $this->controller->getConfig();
    }

    public function setConfig(Config $config): void
    {
        $this->controller->setConfig($config);
    }

    public function getController(): ? AbstractController
    {
        return $this->controller;
    }

    public function claims(array $claims): void
    {
        $this->controller->getAuthenticator()->getJWTObject()->setClaims($claims);
    }

}
