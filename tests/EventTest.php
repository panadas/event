<?php
namespace Panadas\EventModule;

class EventTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers Panadas\EventModule\Event::__construct()
     * @covers Panadas\EventModule\Event::getName()
     * @covers Panadas\EventModule\Event::setName()
     * @covers Panadas\EventModule\Event::getPublisher()
     * @covers Panadas\EventModule\Event::setPublisher()
     * @covers Panadas\EventModule\Event::getParams()
     * @covers Panadas\EventModule\Event::setParams()
     */
    public function testConstruct()
    {
        $name = "foo";
        $publisher = new Publisher();
        $params = ["paramkey" => "paramvalue"];

        $event = new Event($name, $publisher, $params);

        $this->assertEquals($name, $event->getName());
        $this->assertInstanceOf("Panadas\EventModule\DataStructure\EventParams", $event->getParams());
        $this->assertSame($publisher, $event->getPublisher());
    }

    /**
     * @covers Panadas\EventModule\Event::stop()
     * @covers Panadas\EventModule\Event::isStopped()
     * @covers Panadas\EventModule\Event::setStopped()
     */
    public function testStop()
    {
        $event = new Event("foo", new Publisher());

        $this->assertFalse($event->isStopped());
        $event->stop();
        $this->assertTrue($event->isStopped());
    }
}
