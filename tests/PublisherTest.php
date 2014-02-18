<?php
namespace Panadas\Event;

class PublisherTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers Panadas\Event\Publisher::__construct()
     * @covers Panadas\Event\Publisher::getEvents()
     * @covers Panadas\Event\Publisher::setEvents()
     */
    public function testConstruct()
    {
        $publisher = new Publisher();

        $this->assertInstanceOf("Panadas\Event\DataStructure\Events", $publisher->getEvents());
    }

    /**
     * @covers Panadas\Event\Publisher::attach()
     * @covers Panadas\Event\Publisher::subscribe()
     * @covers Panadas\Event\Publisher::normalizeSubscriberConfig()
     * @covers Panadas\Event\Publisher::getSubscriberMethodName()
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
     * @covers Panadas\Event\Publisher::normalizeSubscriberConfig()
     * @dataProvider invalidSubscriberConfigProvider
     * @expectedException InvalidArgumentException
     */
    public function testAttachInvalidSubscriberConfig($config)
    {
        $publisher = new Publisher();

        $subscriber = $this->getMock("Panadas\Event\FooBarSubscriber");
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
     * @covers Panadas\Event\Publisher::detach()
     * @covers Panadas\Event\Publisher::unsubscribe()
     * @covers Panadas\Event\Publisher::normalizeSubscriberConfig()
     * @covers Panadas\Event\Publisher::getSubscriberMethodName()
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
     * @covers Panadas\Event\Publisher::detach()
     * @covers Panadas\Event\Publisher::unsubscribe()
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
     * @covers Panadas\Event\Publisher::publish()
     */
    public function testPublish()
    {
        $publisher = new FooBarPublisher();
        $subscriber = new FooBarSubscriber();

        $publisher->attach($subscriber);

        $event = $publisher->foo();

        $this->assertInstanceOf("Panadas\Event\Event", $event);
        $this->assertEquals("foo", $event->getParams()->get("foobar"));

        $event = $publisher->bar();

        $this->assertInstanceOf("Panadas\Event\Event", $event);
        $this->assertEquals("bar", $event->getParams()->get("foobar"));
    }
}
