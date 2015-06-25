<?php

/*
 * This file is part of the Notifier package
 *
 * (c) InnovationGroup
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FivePercent\Component\Notifier\Metadata;

/**
 * Notification class metadata
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class NotificationMetadata
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $strategy;

    /**
     * @var string
     */
    private $onEvent;

    /**
     * Construct
     *
     * @param string $name
     * @param string $strategy
     * @param string $onEvent
     */
    public function __construct($name, $strategy, $onEvent)
    {
        $this->name = $name;
        $this->strategy = $strategy;
        $this->onEvent = $onEvent;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get strategy
     *
     * @return string
     */
    public function getStrategy()
    {
        return $this->strategy;
    }

    /**
     * Get system event name
     *
     * @return string
     */
    public function getOnEvent()
    {
        return $this->onEvent;
    }
}
