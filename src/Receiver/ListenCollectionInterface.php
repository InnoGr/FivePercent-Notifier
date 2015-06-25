<?php

/*
 * This file is part of the Notifier package
 *
 * (c) InnovationGroup
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FivePercent\Component\Notifier\Receiver;

/**
 * All listen collections should implement this interface
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
interface ListenCollectionInterface
{
    /**
     * Get event names for listen
     *
     * @return array
     */
    public function getEventNames();

    /**
     * Get class name by event name
     *
     * @param string $eventName
     *
     * @return string|null Class name for this event
     */
    public function getClassNameForEvent($eventName);
}
