<?php
namespace Panadas\EventManager;

use Panadas\EventManager\DataStructure\EventParams;

class Event
{

    private $name;
    private $publisher;
    private $params;
    private $stopped = false;

    public function __construct($name, Publisher $publisher, EventParams $params = null)
    {
        if (null === $params) {
            $params = new EventParams();
        }

        $this
            ->setName($name)
            ->setPublisher($publisher)
            ->setParams($params);
    }

    public function getName()
    {
        return $this->name;
    }

    protected function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getPublisher()
    {
        return $this->publisher;
    }

    protected function setPublisher(Publisher $publisher)
    {
        $this->publisher = $publisher;

        return $this;
    }

    public function getParams()
    {
        return $this->params;
    }

    protected function setParams(EventParams $params)
    {
        $this->params = $params;

        return $this;
    }

    public function isStopped()
    {
        return $this->stopped;
    }

    public function setStopped($stopped)
    {
        $this->stopped = (bool) $stopped;

        return $this;
    }

    public function stop()
    {
        return $this->setStopped(true);
    }
}
