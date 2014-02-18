<?php
namespace Panadas\EventModule;

class FooBarSubscriber
{

    public function getSubscribedEvents()
    {
        return [
            [
               "event" => "foo",
               "priority" => 1
            ],
            "bar"
        ];
    }

    public function onFooEvent(Event $event)
    {
        $event->getParams()->set("foobar", "foo");
    }

    public function onBarEvent(Event $event)
    {
        $event->getParams()->set("foobar", "bar");
        $event->stop();
    }
}
