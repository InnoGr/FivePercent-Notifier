<?php

/*
 * This file is part of the Notifier package
 *
 * (c) InnovationGroup
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FivePercent\Component\Notifier;

/**
 * All notifiers should be implemented of this interface
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
interface NotifierInterface
{
    /**
     * Is object supported for notify
     *
     * @param object $object
     * @param string $onEvent
     *
     * @return bool
     */
    public function supportsObject($object, $onEvent = null);

    /**
     * Notify any system with event
     *
     * @param object $object
     * @param string $onEvent
     */
    public function notify($object, $onEvent = null);
}
