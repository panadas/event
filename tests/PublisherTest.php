<?php
namespace Panadas\EventManager;

class PublisherTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers Panadas\EventManager\Publisher::__construct()
     * @covers Panadas\EventManager\Publisher::getEvents()
     * @covers Panadas\EventManager\Publisher::setEvents()
     */
    public function testConstruct()
    {
        $publisher = new Publisher();

        $this->assertInstanceOf("Panadas\EventManager\DataStructure\Events", $publisher->getEvents());
    }

    /**
     * @covers Panadas\EventManager\Publisher::attach()
     * @covers Panadas\EventManager\Publisher::subscribe()
     * @covers Panadas\EventManager\Publisher::normalizeSubscriberConfig()
     * @covers Panadas\EventManager\Publisher::getSubscriberMethodName()
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
     * @covers Panadas\EventManager\Publisher::normalizeSubscriberConfig()
     * @dataProvider invalidSubscriberConfigProvider
     * @expectedException InvalidArgumentException
     */
    public function testAttachInvalidSubscriberConfig($config)
    {
        $publisher = new Publisher();

        $subscriber = $this->getMock("Panadas\EventManager\FooBarSubscriber");
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
     * @covers Panadas\EventManager\Publisher::detach()
     * @covers Panadas\EventManager\Publisher::unsubscribe()
     * @covers Panadas\EventManager\Publisher::normalizeSubscriberConfig()
     * @covers Panadas\EventManager\Publisher::getSubscriberMethodName()
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
     * @covers Panadas\EventManager\Publisher::detach()
     * @covers Panadas\EventManager\Publisher::unsubscribe()
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
     * @covers Panadas\EventManager\Publisher::publish()
     */
    public function testPublish()
    {
        $publisher = new FooBarPublisher();
        $subscriber = new FooBarSubscriber();

        $publisher->attach($subscriber);

        $event = $publisher->foo();

        $this->assertInstanceOf("Panadas\EventManager\Event", $event);
        $this->assertEquals("foo", $event->getParams()->get("foobar"));

        $event = $publisher->bar();

        $this->assertInstanceOf("Panadas\EventManager\Event", $event);
        $this->assertEquals("bar", $event->getParams()->get("foobar"));
    }
}
