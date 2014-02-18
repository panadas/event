<?php
namespace Panadas\EventManager;

class EventTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers Panadas\EventManager\Event::__construct()
     * @covers Panadas\EventManager\Event::getName()
     * @covers Panadas\EventManager\Event::setName()
     * @covers Panadas\EventManager\Event::getPublisher()
     * @covers Panadas\EventManager\Event::setPublisher()
     * @covers Panadas\EventManager\Event::getParams()
     * @covers Panadas\EventManager\Event::setParams()
     */
    public function testConstruct()
    {
        $name = "foo";
        $publisher = new Publisher();
        $params = ["paramkey" => "paramvalue"];

        $event = new Event($name, $publisher, $params);

        $this->assertEquals($name, $event->getName());
        $this->assertInstanceOf("Panadas\EventManager\DataStructure\EventParams", $event->getParams());
        $this->assertSame($publisher, $event->getPublisher());
    }

    /**
     * @covers Panadas\EventManager\Event::stop()
     * @covers Panadas\EventManager\Event::isStopped()
     * @covers Panadas\EventManager\Event::setStopped()
     */
    public function testStop()
    {
        $event = new Event("foo", new Publisher());

        $this->assertFalse($event->isStopped());
        $event->stop();
        $this->assertTrue($event->isStopped());
    }
}
