<?php
namespace Panadas\EventModule;

class FooBarPublisher extends Publisher
{

    public function foo(array $params = [])
    {
        return $this->publish("foo", $params);
    }

    public function bar(array $params = [])
    {
        return $this->publish("bar", $params);
    }
}
