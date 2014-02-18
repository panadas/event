<?php
namespace Panadas\Event;

use Panadas\Event\DataStructure\EventParams;
use Panadas\Event\DataStructure\Events;
use Panadas\Event\DataStructure\Subscribers;
use Panadas\Util\String;

class Publisher
{

    private $events;

    const WHEN_BEFORE  = "before";
    const WHEN_AFTER   = "after";
    const WHEN_DEFAULT = self::WHEN_BEFORE;

    public function __construct(Events $events = null)
    {
        if (null === $events) {
            $events = new Events();
        }

        $this->setEvents($events);
    }

    public function getEvents()
    {
        return $this->events;
    }

    protected function setEvents(Events $events)
    {
        $this->events = $events;

        return $this;
    }

    public function before($event, callable $callback, $priority = 0)
    {
        return $this->subscribe($event, static::WHEN_BEFORE, $callback, $priority);
    }

    public function after($event, callable $callback, $priority = 0)
    {
        return $this->subscribe($event, static::WHEN_AFTER, $callback, $priority);
    }

    public function subscribe($event, $when, callable $callback, $priority = 0)
    {
        $events = $this->getEvents();

        $key = static::getEventSubscriberKey($event, $when);

        if (!$events->has($key)) {
            $events->set($key, new Subscribers());
        }

        $subscribers = $events->get($key);
        $subscribers->append($subscribers::create($callback, $priority));

        return $this;
    }

    public function unsubscribe($event, $when, callable $callback, $priority = 0)
    {
        $events = $this->getEvents();

        $key = static::getEventSubscriberKey($event, $when);

        if ($events->has($key)) {
            $subscribers = $events->get($key);
            $subscribers->remove($subscribers::create($callback, $priority));
        }

        return $this;
    }

    protected function callListeners(Event $event, Subscribers $subscribers)
    {
        foreach ($subscribers as $subscriber) {

            $subscriber["callback"]($event);

            if ($event->isStopped()) {
                break;
            }

        }

        return $this;
    }

    protected function publish($name, callable $callback, EventParams $params = null)
    {
        if (null === $params) {
            $params = new EventParams();
        }

        $event = new Event($name, $this, $params);

        $subscribers = $this->getEvents()->get(static::getEventSubscriberKey($name, static::WHEN_BEFORE));
        if (null !== $subscribers) {
            $this->callListeners($event, $subscribers->sort());
        }

        $callback($event);

        $subscribers = $this->getEvents()->get(static::getEventSubscriberKey($name, static::WHEN_AFTER));
        if (null !== $subscribers) {
            $this->callListeners($event, $subscribers->sort());
        }

        return $event;
    }

    protected static function getEventSubscriberKey($event, $when)
    {
        return "{$event}:{$when}";
    }
}
