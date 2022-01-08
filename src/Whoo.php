<?php

namespace Abdyek\Whoo;

use Abdyek\Whoo\Core\Controller;

class Whoo
{
    public bool $success = true;
    private Controller $controller;
    private array $callbacks = [];

    public function __construct(string $controller, array $args)
    {
        $class = 'Abdyek\\Whoo\\Controller\\' . $controller;
        $this->controller = new $class();
        $data = $this->controller->getData();
        $data->setContent($args);
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

}
