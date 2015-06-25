<?php

/*
 * This file is part of the Notifier package
 *
 * (c) InnovationGroup
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FivePercent\Component\Notifier\Sender;

use FivePercent\Component\Notifier\Notification;

/**
 * All senders should be implemented of this interface
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
interface SenderInterface
{
    /**
     * Send event to storage
     *
     * @param Notification $notification
     */
    public function send(Notification $notification);
}
