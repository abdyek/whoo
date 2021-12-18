<?php

namespace Abdyek\Whoo;

use Abdyek\Whoo\Core\Controller;

class Whoo
{
    public bool $success = true;
    private string $controllerString;
    private ? Controller $controller = null;
    private ? \Exception $exception = null;
    private array $args;
    private array $callbacks = [];

    public function __construct(string $controller, array $args)
    {
        $this->controllerString = $controller;
        $this->args = $args;
    }

    public function catchException(string $exception, object $callback)
    {
        $this->callbacks[$exception] = $callback;
        return $this;
    }

    public function run(): void
    {
        try {
            $class = 'Abdyek\\Whoo\\Controller\\' . $this->controllerString;
            $this->controller = new $class($this->args);
        } catch(\Exception $e) {
            $this->success = false;
            $this->exception = $e;
            $pieces = explode('\\', get_class($e));
            $pieces = explode('Exception', end($pieces));
            ($this->callbacks[$pieces[0]])();
        }
    }

    public function getController(): ? Controller
    {
        return $this->controller;
    }

    public function getException(): ? \Exception
    {
        return $this->exception;
    }

}
