<?php
namespace Panadas\Event;

class EventTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers Panadas\Event\Event::__construct()
     * @covers Panadas\Event\Event::getName()
     * @covers Panadas\Event\Event::setName()
     * @covers Panadas\Event\Event::getPublisher()
     * @covers Panadas\Event\Event::setPublisher()
     * @covers Panadas\Event\Event::getParams()
     * @covers Panadas\Event\Event::setParams()
     */
    public function testConstruct()
    {
        $name = "foo";
        $publisher = new Publisher();
        $params = ["paramkey" => "paramvalue"];

        $event = new Event($name, $publisher, $params);

        $this->assertEquals($name, $event->getName());
        $this->assertInstanceOf("Panadas\Event\DataStructure\EventParams", $event->getParams());
        $this->assertSame($publisher, $event->getPublisher());
    }

    /**
     * @covers Panadas\Event\Event::stop()
     * @covers Panadas\Event\Event::isStopped()
     * @covers Panadas\Event\Event::setStopped()
     */
    public function testStop()
    {
        $event = new Event("foo", new Publisher());

        $this->assertFalse($event->isStopped());
        $event->stop();
        $this->assertTrue($event->isStopped());
    }
}
