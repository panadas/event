<?php
namespace Panadas\EventModule;

class PublisherTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers Panadas\EventModule\Publisher::__construct()
     * @covers Panadas\EventModule\Publisher::getEvents()
     * @covers Panadas\EventModule\Publisher::setEvents()
     */
    public function testConstruct()
    {
        $publisher = new Publisher();

        $this->assertInstanceOf("Panadas\EventModule\DataStructure\Events", $publisher->getEvents());
    }

    /**
     * @covers Panadas\EventModule\Publisher::attach()
     * @covers Panadas\EventModule\Publisher::subscribe()
     * @covers Panadas\EventModule\Publisher::normalizeSubscriberConfig()
     * @covers Panadas\EventModule\Publisher::getSubscriberMethodName()
     */
    public function testAttach()
    {
        $publisher = new Publisher();
        $subscriber = new FooBarSubscriber();

        $publisher->attach($subscriber);

        $events = $publisher->getEvents();

        $this->assertTrue($events->get("foo")->populated());
        $this->assertTrue($events->get("bar")->populated());
    }

    /**
     * @covers Panadas\EventModule\Publisher::normalizeSubscriberConfig()
     * @dataProvider invalidSubscriberConfigProvider
     * @expectedException InvalidArgumentException
     */
    public function testAttachInvalidSubscriberConfig($config)
    {
        $publisher = new Publisher();

        $subscriber = $this->getMock("Panadas\EventModule\FooBarSubscriber");
        $subscriber
            ->expects($this->any())
            ->method("getSubscribedEvents")
            ->will($this->returnValue($config));

        $publisher->attach($subscriber);
    }

    public function invalidSubscriberConfigProvider()
    {
        return [
            [[null]],
            [[123]],
            [[new \stdClass()]],
            [[["foo" => "bar"]]]
        ];
    }

    /**
     * @covers Panadas\EventModule\Publisher::detach()
     * @covers Panadas\EventModule\Publisher::unsubscribe()
     * @covers Panadas\EventModule\Publisher::normalizeSubscriberConfig()
     * @covers Panadas\EventModule\Publisher::getSubscriberMethodName()
     */
    public function testDetach()
    {
        $publisher = new Publisher();
        $subscriber = new FooBarSubscriber();

        $publisher
            ->attach($subscriber)
            ->detach($subscriber);

        $events = $publisher->getEvents();

        $this->assertFalse($events->get("foo")->populated());
        $this->assertFalse($events->get("bar")->populated());
    }

    /**
     * @covers Panadas\EventModule\Publisher::detach()
     * @covers Panadas\EventModule\Publisher::unsubscribe()
     */
    public function testDetachUnattached()
    {
        $publisher = new Publisher();
        $subscriber = new FooBarSubscriber();

        $publisher->detach($subscriber);

        $events = $publisher->getEvents();

        $this->assertFalse($events->has("foo"));
        $this->assertFalse($events->has("bar"));
    }

    /**
     * @covers Panadas\EventModule\Publisher::publish()
     */
    public function testPublish()
    {
        $publisher = new FooBarPublisher();
        $subscriber = new FooBarSubscriber();

        $publisher->attach($subscriber);

        $event = $publisher->foo();

        $this->assertInstanceOf("Panadas\EventModule\Event", $event);
        $this->assertEquals("foo", $event->getParams()->get("foobar"));

        $event = $publisher->bar();

        $this->assertInstanceOf("Panadas\EventModule\Event", $event);
        $this->assertEquals("bar", $event->getParams()->get("foobar"));
    }
}
