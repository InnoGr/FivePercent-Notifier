<?php

/*
 * This file is part of the Notifier package
 *
 * (c) InnovationGroup
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FivePercent\Component\Notifier\EventDispatcher;

use FivePercent\Component\Notifier\NotifierInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Override Symfony2 event dispatcher for send notifications on "dispatch" symfony events
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class EventDispatcherNotifierProxy implements EventDispatcherInterface
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var NotifierInterface
     */
    private $notifier;

    /**
     * @var array
     */
    private $disableNotifyEventNames = [];

    /**
     * Construct
     *
     * @param EventDispatcherInterface $eventDispatcher
     * @param NotifierInterface        $notifier
     * @param array                    $disableNotifyEventNames
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        NotifierInterface $notifier,
        array $disableNotifyEventNames = []
    ) {
        $this->dispatcher = $eventDispatcher;
        $this->notifier = $notifier;
        $this->disableNotifyEventNames = $disableNotifyEventNames;
    }

    /**
     * Get public event dispatcher
     *
     * @return EventDispatcherInterface
     */
    public function getRealEventDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * {@inheritDoc}
     */
    public function dispatch($eventName, Event $event = null)
    {
        $event = $this->dispatcher->dispatch($eventName, $event);

        if (!in_array($eventName, $this->disableNotifyEventNames) &&
            $this->notifier->supportsObject($event, $eventName)
        ) {
            $this->notifier->notify($event, $eventName);
        }

        return $event;
    }

    /**
     * {@inheritDoc}
     */
    public function addListener($eventName, $listener, $priority = 0)
    {
        $this->dispatcher->addListener($eventName, $listener, $priority);
    }

    /**
     * {@inheritDoc}
     */
    public function addSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->dispatcher->addSubscriber($subscriber);
    }

    /**
     * {@inheritDoc}
     */
    public function removeListener($eventName, $listener)
    {
        $this->dispatcher->removeListener($eventName, $listener);
    }

    /**
     * {@inheritDoc}
     */
    public function removeSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->dispatcher->removeSubscriber($subscriber);
    }

    /**
     * {@inheritDoc}
     */
    public function getListeners($eventName = null)
    {
        return $this->dispatcher->getListeners($eventName);
    }

    /**
     * {@inheritDoc}
     */
    public function hasListeners($eventName = null)
    {
        return $this->dispatcher->hasListeners($eventName);
    }

    /**
     * Proxies all method calls to the original event dispatcher.
     *
     * @param string $method    The method name
     * @param array  $arguments The method arguments
     *
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return call_user_func_array(array($this->dispatcher, $method), $arguments);
    }
}
