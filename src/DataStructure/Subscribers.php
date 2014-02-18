<?php
namespace Panadas\Event\DataStructure;

use Panadas\DataStructure\ArrayList;

class Subscribers extends ArrayList
{

    const SORT_PRIORITY = -1;

    protected function filter(&$item)
    {
        if (is_callable($item)) {
            $item = [
                "callback" => $item
            ];
        } elseif (!is_array($item)) {
            return;
        } elseif (!array_key_exists("callback", $item)) {
            $item["callback"] = null;
        }

        if (!array_key_exists("priority", $item)) {
            $item["priority"] = 0;
        }
    }

    protected function validate($item)
    {
        if (!is_array($item)) {
            throw new \InvalidArgumentException("Event listener must be an array");
        }

        if (!is_callable($item["callback"])) {
            throw new \InvalidArgumentException("Event listeners must provide a callback");
        }

        return true;
    }

    public function sort($flags = self::SORT_PRIORITY)
    {
        if (static::SORT_PRIORITY === $flags) {
            return $this->usort([$this, "usortPriority"]);
        }

        return parent::sort($flags);
    }

    protected function usortPriority(array $item1, array $item2)
    {
        switch (true) {
            case ($item1["priority"] < $item2["priority"]):
                return 1;
            case ($item1["priority"] > $item2["priority"]):
                return -1;
            default:
                return 0;
        }
    }

    public static function create(callable $callback = null, $priority = 0)
    {
        return [
            "callback" => $callback,
            "priority" => $priority
        ];
    }
}
