<?php
namespace Panadas\Event\DataStructure;

use Panadas\Event\DataStructure\Subscribers;
use Panadas\Event\Event;

class SubscribersTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers Panadas\Event\DataStructure\Subscribers::create()
     */
    public function testCreate()
    {
        $arrayList = new Subscribers();

        $callback = function () {};

        $item = $arrayList::create($callback);
        $this->assertEquals(2, count($item));
        $this->assertSame($callback, $item["callback"]);
        $this->assertEquals(0, $item["priority"]);

        $priority = 10;

        $item = $arrayList::create($callback, $priority);
        $this->assertEquals($priority, $item["priority"]);
    }

    /**
     * @covers Panadas\Event\DataStructure\Subscribers::filter()
     */
    public function testFilter()
    {
        $arrayList = new Subscribers();

        $callback = function () {};

        $arrayList->append($callback);
        $arrayList->append($arrayList::create($callback));
    }

    /**
     * @covers Panadas\Event\DataStructure\Subscribers::filter()
     * @expectedException InvalidArgumentException
     */
    public function testFilterInvalidSubscriber()
    {
        $arrayList = new Subscribers();
        $arrayList->append("foo");
    }

    /**
     * @covers Panadas\Event\DataStructure\Subscribers::sort()
     * @covers Panadas\Event\DataStructure\Subscribers::usortPriority()
     */
    public function testSort()
    {
        $arrayList = new Subscribers();
        $callback = function (Event $event) {};

        $subscriber1 = $arrayList::create($callback, 1);
        $subscriber2 = $arrayList::create($callback, 2);
        $subscriber3 = $arrayList::create($callback, 3);
        $subscriber4 = $arrayList::create($callback, 2);

        $arrayList->append($subscriber1);
        $arrayList->append($subscriber2);
        $arrayList->append($subscriber3);
        $arrayList->append($subscriber4);

        $arrayList->sort();
        $this->assertSame([$subscriber3, $subscriber4, $subscriber2, $subscriber1], $arrayList->all());

        $arrayList->sort(SORT_REGULAR);
        $this->assertSame([$subscriber1, $subscriber2, $subscriber4, $subscriber3], $arrayList->all());
    }
}
