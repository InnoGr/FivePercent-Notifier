<?php

/**
 * This file is part of the Notifier package
 *
 * (c) InnovationGroup
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FivePercent\Component\Notifier\Receiver;

/**
 * Base listen collection
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class ListenCollection implements ListenCollectionInterface
{
    /**
     * @var array
     */
    private $listens = [];

    /**
     * Construct
     *
     * @param array $listens
     */
    public function __construct(array $listens = [])
    {
        foreach ($listens as $eventName => $class) {
            $this->listen($eventName, $class);
        }
    }

    /**
     * Add listen event
     *
     * @param string $event
     * @param string $class
     *
     * @return ListenCollection
     */
    public function listen($event, $class)
    {
        $this->listens[$event] = $class;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getEventNames()
    {
        return array_keys($this->listens);
    }

    /**
     * {@inheritDoc}
     */
    public function getClassNameForEvent($eventName)
    {
        return isset($this->listens[$eventName]) ? $this->listens[$eventName] : null;
    }
}
