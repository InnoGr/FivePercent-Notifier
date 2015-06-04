<?php

/**
 * This file is part of the Notifier package
 *
 * (c) InnovationGroup
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FivePercent\Component\Notifier\Receiver\Adapter;

/**
 * Adapter for receive entry from queue system
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
interface ReceiverAdapterInterface
{
    /**
     * Receive process for event names.
     * The callback returns true, if message success acknowledge and false if not acknowledge.
     *
     * @param array    $eventNames
     * @param \Closure $callback
     */
    public function receive(array $eventNames, \Closure $callback);
}
